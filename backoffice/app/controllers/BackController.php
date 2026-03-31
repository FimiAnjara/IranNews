<?php
require_once __DIR__ . '/../models/News.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Category.php';

class BackController {
    private $newsModel;
    private $userModel;
    private $categoryModel;

    public function __construct() {
        $this->newsModel = new News();
        $this->userModel = new User();
        $this->categoryModel = new Category();
        $this->checkAuth();
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . backUrl('connexion'));
            exit;
        }
    }

    public function dashboard() {
        $allNews = $this->newsModel->getAll(20);
        $publishedNews = $this->newsModel->getAllPublished(5);
        $draftNews = $this->newsModel->getAllDrafts(5);
        
        return [
            'view' => 'back/dashboard.php',
            'data' => [
                'allNews' => $allNews,
                'publishedNews' => $publishedNews,
                'draftNews' => $draftNews,
                'totalArticles' => count($allNews),
            ]
        ];
    }

    public function newsList() {
        $pageNum = (int)($_GET['p'] ?? 1);
        $limit = 5;
        $offset = ($pageNum - 1) * $limit;
        
        // Récupérer les filtres de la requête GET
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $categoryId = $_GET['category_id'] ?? '';
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        
        // Appliquer les filtres
        $news = $this->newsModel->getFiltered($search, $status, $categoryId, $dateFrom, $dateTo, $limit, $offset);
        
        // Compter le total d'articles filtrés
        $totalNews = $this->newsModel->countFiltered($search, $status, $categoryId, $dateFrom, $dateTo);
        $totalPages = ceil($totalNews / $limit);
        
        // Récupérer les catégories pour le filtre
        $categories = $this->categoryModel->getAll();
        
        return [
            'view' => 'back/news/list.php',
            'data' => [
                'news' => $news,
                'page' => $pageNum,
                'limit' => $limit,
                'totalPages' => $totalPages,
                'totalNews' => $totalNews,
                'categories' => $categories,
                'filters' => [
                    'search' => $search,
                    'status' => $status,
                    'category_id' => $categoryId,
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo
                ]
            ]
        ];
    }

    public function newsShow($id) {
        $news = $this->newsModel->getByIdForAdmin($id);

        if (!$news) {
            return [
                'view' => 'errors/404.php',
                'data' => ['message' => 'Article non trouvé']
            ];
        }

        $images = $this->newsModel->getAllImages($id);

        return [
            'view' => 'back/news/show.php',
            'data' => [
                'news' => $news,
                'images' => $images,
                'page_title' => 'Détail article - ' . htmlspecialchars($news['title'])
            ]
        ];
    }

    public function newsTogglePublish($id) {
        $news = $this->newsModel->getByIdForAdmin($id);
        if (!$news) {
            header('Location: ' . adminUrl('news-show', $id));
            exit;
        }
        
        // Basculer le statut (0 => 1, 1 => 0)
        $newStatus = $news['etat'] ? 0 : 1;
        $this->newsModel->updateStatus($id, $newStatus);
        
        header('Location: ' . adminUrl('news-list'));
        exit;
    }

    public function newsCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';
            $categoryId = $_POST['category_id'] ?? null;
            $description = $_POST['description'] ?? '';
            $autor = $_POST['autor'] ?? '';
            $etat = isset($_POST['published']) ? 1 : 0;
            $imagesAltText = trim($_POST['images_alt_text'] ?? '');

            if (empty($title) || empty($content)) {
                return [
                    'view' => 'back/news/create.php',
                    'data' => [
                        'error' => 'Titre et contenu requis',
                        'suppress_global_alert' => true
                    ]
                ];
            }

            // Créer l'article
            $newsId = $this->newsModel->create($title, $content, $_SESSION['user_id'], $categoryId, $description, $etat, $autor);
            
            // Gérer les uploads d'images
            if (!empty($_FILES['images']['name'][0])) {
                $uploadErrors = $this->handleImageUpload($newsId, $_FILES['images'], $imagesAltText);
                if (!empty($uploadErrors)) {
                    $news = $this->newsModel->getByIdForAdmin($newsId);
                    $categories = $this->categoryModel->getAll();

                    return [
                        'view' => 'back/news/edit.php',
                        'data' => [
                            'news' => $news,
                            'id' => $newsId,
                            'categories' => $categories,
                            'error' => 'Upload image échoué: ' . implode(' | ', $uploadErrors),
                            'suppress_global_alert' => true
                        ]
                    ];
                }
            }

            header('Location: ' . adminUrl('news-list'));
            exit;
        }

        $categories = $this->categoryModel->getAll();
        
        return [
            'view' => 'back/news/create.php',
            'data' => [
                'categories' => $categories,
                'suppress_global_alert' => true
            ]
        ];
    }

    public function newsEdit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';
            $categoryId = $_POST['category_id'] ?? null;
            $description = $_POST['description'] ?? '';
            $autor = $_POST['autor'] ?? '';
            $etat = isset($_POST['published']) ? 1 : 0;
            $imagesAltText = trim($_POST['images_alt_text'] ?? '');

            if (empty($title) || empty($content)) {
                return [
                    'view' => 'back/news/edit.php',
                    'data' => [
                        'error' => 'Titre et contenu requis',
                        'id' => $id,
                        'suppress_global_alert' => true
                    ]
                ];
            }

            $this->newsModel->update($id, $title, $content, $categoryId, $description, $etat, $autor);

            // Gérer les uploads d'images
            if (!empty($_FILES['images']['name'][0])) {
                $uploadErrors = $this->handleImageUpload($id, $_FILES['images'], $imagesAltText);
                if (!empty($uploadErrors)) {
                    $news = $this->newsModel->getByIdForAdmin($id);
                    $categories = $this->categoryModel->getAll();

                    return [
                        'view' => 'back/news/edit.php',
                        'data' => [
                            'news' => $news,
                            'id' => $id,
                            'categories' => $categories,
                            'error' => 'Upload image échoué: ' . implode(' | ', $uploadErrors),
                            'suppress_global_alert' => true
                        ]
                    ];
                }
            }

            header('Location: ' . adminUrl('news-list'));
            exit;
        }

        $news = $this->newsModel->getByIdForAdmin($id);
        
        if (!$news) {
            return [
                'view' => 'errors/404.php',
                'data' => ['message' => 'Article non trouvé']
            ];
        }
        
        $categories = $this->categoryModel->getAll();

        return [
            'view' => 'back/news/edit.php',
            'data' => [
                'news' => $news,
                'id' => $id,
                'categories' => $categories,
                'suppress_global_alert' => true
            ]
        ];
    }

    public function newsDelete($id) {
        $this->newsModel->delete($id);
        header('Location: ' . adminUrl('news-list'));
        exit;
    }

    public function mediaUpdate($imageId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . adminUrl('news-list'));
            exit;
        }

        $image = $this->newsModel->getImageById($imageId);
        if (!$image) {
            return [
                'view' => 'errors/404.php',
                'data' => ['message' => 'Image non trouvée']
            ];
        }

        $articleId = (int)$image['article_id'];
        $article = $this->newsModel->getByIdForAdmin($articleId);
        $articleTitle = $article['title'] ?? null;

        $altText = trim($_POST['alt_text'] ?? '');
        if ($altText === '') {
            $altText = $articleTitle ?: ($image['alt_text'] ?? null);
        }

        $errors = [];
        $newUrl = null;

        if (!empty($_FILES['image']['name'])) {
            $file = $_FILES['image'];
            $errorCode = $file['error'] ?? UPLOAD_ERR_NO_FILE;
            if ($errorCode === UPLOAD_ERR_OK) {
                $fileSize = $file['size'] ?? 0;
                $fileType = $file['type'] ?? '';
                $tmpName = $file['tmp_name'] ?? '';
                $fileName = $file['name'] ?? 'image';

                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $maxFileSize = 5 * 1024 * 1024;
                $maxFileSizeMb = (int)($maxFileSize / 1024 / 1024);

                if (!in_array($fileType, $allowedTypes)) {
                    $errors[] = $fileName . ' (format non autorise)';
                } elseif ($fileSize > $maxFileSize) {
                    $errors[] = $fileName . ' (taille > ' . $maxFileSizeMb . ' Mo)';
                } elseif (!is_uploaded_file($tmpName)) {
                    $errors[] = $fileName . ' (fichier invalide)';
                } else {
                    $uploadDir = __DIR__ . '/../../../public/uploads/articles/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                    $uniqueName = 'article-' . $articleId . '-' . time() . '-' . uniqid() . '.' . $ext;
                    $uploadPath = $uploadDir . $uniqueName;

                    if (move_uploaded_file($tmpName, $uploadPath)) {
                        $this->optimizeImage($uploadPath, $fileType);
                        $newUrl = '/uploads/articles/' . $uniqueName;
                    } else {
                        $errors[] = $fileName . ' (impossible de sauvegarder)';
                    }
                }
            } elseif ($errorCode !== UPLOAD_ERR_NO_FILE) {
                $errors[] = ($file['name'] ?? 'image') . ' (erreur upload)';
            }
        }

        if (!empty($errors)) {
            $images = $this->newsModel->getAllImages($articleId);
            return [
                'view' => 'back/news/show.php',
                'data' => [
                    'news' => $article,
                    'images' => $images,
                    'error' => 'Mise a jour image echouee: ' . implode(' | ', $errors)
                ]
            ];
        }

        $this->newsModel->updateImage($imageId, $newUrl, $altText);
        $images = $this->newsModel->getAllImages($articleId);

        return [
            'view' => 'back/news/show.php',
            'data' => [
                'news' => $article,
                'images' => $images,
                'success' => 'Image mise a jour avec succes'
            ]
        ];
    }

    public function mediaDelete($imageId) {
        $image = $this->newsModel->getImageById($imageId);
        if (!$image) {
            return [
                'view' => 'errors/404.php',
                'data' => ['message' => 'Image non trouvée']
            ];
        }

        $articleId = (int)$image['article_id'];
        $this->newsModel->deleteImage($imageId);

        $article = $this->newsModel->getByIdForAdmin($articleId);
        $images = $this->newsModel->getAllImages($articleId);

        return [
            'view' => 'back/news/show.php',
            'data' => [
                'news' => $article,
                'images' => $images,
                'success' => 'Image supprimee'
            ]
        ];
    }

    public function usersList() {
        $pageNum = (int)($_GET['p'] ?? 1);
        $limit = 5;
        $offset = ($pageNum - 1) * $limit;
        
        $users = $this->userModel->getAllPaginated($limit, $offset);
        $totalUsers = $this->userModel->count();
        $totalPages = ceil($totalUsers / $limit);
        
        return [
            'view' => 'back/users/list.php',
            'data' => [
                'users' => $users,
                'page' => $pageNum,
                'limit' => $limit,
                'totalPages' => $totalPages,
                'totalUsers' => $totalUsers
            ]
        ];
    }

    private function handleImageUpload($articleId, $files, $imagesAltText = '') {
        $article = $this->newsModel->getByIdForAdmin($articleId);
        $articleTitle = $article['title'] ?? null;
        $errors = [];

        // Créer le dossier uploads s'il n'existe pas
        $uploadDir = __DIR__ . '/../../../public/uploads/articles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        $maxFileSizeMb = (int)($maxFileSize / 1024 / 1024);

        for ($i = 0; $i < count($files['name']); $i++) {
            $fileName = $files['name'][$i] ?? 'image';
            $errorCode = $files['error'][$i] ?? UPLOAD_ERR_NO_FILE;

            if ($errorCode === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if ($errorCode !== UPLOAD_ERR_OK) {
                $errors[] = $fileName . ' (erreur upload)';
                continue;
            }

            $fileSize = $files['size'][$i];
            $fileType = $files['type'][$i];
            $tmpName = $files['tmp_name'][$i];

            // Valider le fichier
            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = $fileName . ' (format non autorisé)';
                continue;
            }
            if ($fileSize > $maxFileSize) {
                $errors[] = $fileName . ' (taille > ' . $maxFileSizeMb . ' Mo)';
                continue;
            }
            if (!is_uploaded_file($tmpName)) {
                $errors[] = $fileName . ' (fichier invalide)';
                continue;
            }

            // Générer un nom unique
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueName = 'article-' . $articleId . '-' . time() . '-' . uniqid() . '.' . $ext;
            $uploadPath = $uploadDir . $uniqueName;

            // Déplacer le fichier temporaire
            if (move_uploaded_file($tmpName, $uploadPath)) {
                // Optimiser l'image (redimensionner et compresser)
                $this->optimizeImage($uploadPath, $fileType);
                
                // Insérer l'image dans la base de données
                $publicUrl = '/uploads/articles/' . $uniqueName;
                $altText = $imagesAltText !== '' ? $imagesAltText : ($articleTitle ?: $fileName);
                $this->newsModel->insertImage($articleId, $publicUrl, $altText);
            } else {
                $errors[] = $fileName . ' (impossible de sauvegarder)';
            }
        }

        return $errors;
    }

    private function optimizeImage($filePath, $mimeType) {
        // Configuration d'optimisation
        $maxWidth = 1600;
        $maxHeight = 900;
        $jpegQuality = 80;
        $pngCompression = 7;

        // Vérifier si GD est disponible
        if (!extension_loaded('gd') || !function_exists('imagecreatefromjpeg')) {
            return; // GD non disponible, garder l'image originale
        }

        try {
            // Charger l'image selon son type
            $image = null;
            
            if ($mimeType === 'image/jpeg' && function_exists('imagecreatefromjpeg')) {
                $image = @imagecreatefromjpeg($filePath);
            } elseif ($mimeType === 'image/png' && function_exists('imagecreatefrompng')) {
                $image = @imagecreatefrompng($filePath);
            } elseif ($mimeType === 'image/gif' && function_exists('imagecreatefromgif')) {
                $image = @imagecreatefromgif($filePath);
            } elseif ($mimeType === 'image/webp' && function_exists('imagecreatefromwebp')) {
                $image = @imagecreatefromwebp($filePath);
            }

            if (!$image) {
                return; // Impossible de charger l'image
            }

            // Obtenir les dimensions
            $width = @imagesx($image);
            $height = @imagesy($image);

            if (!$width || !$height) {
                @imagedestroy($image);
                return;
            }

            // Redimensionner si l'image depasse les limites
            if ($width > $maxWidth || $height > $maxHeight) {
                $resized = null;

                if ($width >= $maxWidth && $height >= $maxHeight) {
                    $scale = max($maxWidth / $width, $maxHeight / $height);
                    $scaledWidth = (int)round($width * $scale);
                    $scaledHeight = (int)round($height * $scale);

                    $resized = @imagecreatetruecolor($scaledWidth, $scaledHeight);
                    if (!$resized) {
                        @imagedestroy($image);
                        return;
                    }

                    if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
                        @imagealphablending($resized, false);
                        @imagesavealpha($resized, true);
                    }

                    @imagecopyresampled($resized, $image, 0, 0, 0, 0, $scaledWidth, $scaledHeight, $width, $height);

                    $cropX = max(0, (int)floor(($scaledWidth - $maxWidth) / 2));
                    $cropY = max(0, (int)floor(($scaledHeight - $maxHeight) / 2));

                    $cropped = @imagecreatetruecolor($maxWidth, $maxHeight);
                    if (!$cropped) {
                        @imagedestroy($resized);
                        @imagedestroy($image);
                        return;
                    }

                    if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
                        @imagealphablending($cropped, false);
                        @imagesavealpha($cropped, true);
                    }

                    @imagecopy($cropped, $resized, 0, 0, $cropX, $cropY, $maxWidth, $maxHeight);
                    @imagedestroy($resized);
                    $resized = $cropped;
                } else {
                    $ratio = min($maxWidth / $width, $maxHeight / $height, 1);
                    $newWidth = (int)round($width * $ratio);
                    $newHeight = (int)round($height * $ratio);

                    $resized = @imagecreatetruecolor($newWidth, $newHeight);
                    if (!$resized) {
                        @imagedestroy($image);
                        return;
                    }

                    if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
                        @imagealphablending($resized, false);
                        @imagesavealpha($resized, true);
                    }

                    @imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                }

                if ($mimeType === 'image/jpeg' && function_exists('imagejpeg')) {
                    @imagejpeg($resized, $filePath, $jpegQuality);
                } elseif ($mimeType === 'image/png' && function_exists('imagepng')) {
                    @imagepng($resized, $filePath, $pngCompression);
                } elseif ($mimeType === 'image/gif' && function_exists('imagegif')) {
                    @imagegif($resized, $filePath);
                } elseif ($mimeType === 'image/webp' && function_exists('imagewebp')) {
                    @imagewebp($resized, $filePath, $jpegQuality);
                }

                @imagedestroy($resized);
            }

            @imagedestroy($image);

            // Clear image cache
            clearstatcache();
        } catch (Exception $e) {
            // Silencieusement échouer si l'optimisation pose problème
            error_log('Image optimization error: ' . $e->getMessage());
        }
    }
}

