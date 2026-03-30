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
        $limit = 10;
        $offset = ($pageNum - 1) * $limit;
        
        // Récupérer les filtres de la requête GET
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $categoryId = $_GET['category_id'] ?? '';
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        
        // Appliquer les filtres
        $news = $this->newsModel->getFiltered($search, $status, $categoryId, $dateFrom, $dateTo, $limit, $offset);
        
        // Récupérer les catégories pour le filtre
        $categories = $this->categoryModel->getAll();
        
        return [
            'view' => 'back/news/list.php',
            'data' => [
                'news' => $news,
                'page' => $pageNum,
                'limit' => $limit,
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

        return $this->buildNewsShowResponse($news, $images);
    }

    public function mediaUpdate($mediaId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . adminUrl('news-list'));
            exit;
        }

        $media = $this->newsModel->getMediaById($mediaId);
        if (!$media) {
            header('Location: ' . adminUrl('news-list'));
            exit;
        }

        $articleId = (int)$media['article_id'];
        $news = $this->newsModel->getByIdForAdmin($articleId);
        if (!$news) {
            header('Location: ' . adminUrl('news-list'));
            exit;
        }

        if (empty($_FILES['image'])) {
            $images = $this->newsModel->getAllImages($articleId);
            return $this->buildNewsShowResponse($news, $images, 'Aucune image sélectionnée.');
        }

        $updateResult = $this->handleSingleImageUpdate($articleId, $mediaId, $_FILES['image'], $news['title'] ?? null);
        if ($updateResult !== true) {
            $images = $this->newsModel->getAllImages($articleId);
            return $this->buildNewsShowResponse($news, $images, $updateResult);
        }

        header('Location: ' . adminUrl('news-show', $articleId));
        exit;
    }

    public function mediaDelete($mediaId) {
        $media = $this->newsModel->getMediaById($mediaId);
        if (!$media) {
            header('Location: ' . adminUrl('news-list'));
            exit;
        }

        $this->newsModel->deleteMedia($mediaId);

        header('Location: ' . adminUrl('news-show', (int)$media['article_id']));
        exit;
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
                $uploadErrors = $this->handleImageUpload($newsId, $_FILES['images']);
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
                $uploadErrors = $this->handleImageUpload($id, $_FILES['images']);
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

    public function usersList() {
        $users = $this->userModel->getAll();
        return [
            'view' => 'back/users/list.php',
            'data' => ['users' => $users]
        ];
    }

    private function handleImageUpload($articleId, $files) {
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
                $altText = $articleTitle ?: $fileName;
                $this->newsModel->insertImage($articleId, $publicUrl, $altText);
            } else {
                $errors[] = $fileName . ' (impossible de sauvegarder)';
            }
        }

        return $errors;
    }

    private function handleSingleImageUpdate($articleId, $mediaId, $file, $articleTitle) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        $maxFileSizeMb = (int)($maxFileSize / 1024 / 1024);

        $fileName = $file['name'] ?? 'image';
        $errorCode = $file['error'] ?? UPLOAD_ERR_NO_FILE;

        if ($errorCode === UPLOAD_ERR_NO_FILE) {
            return 'Aucune image sélectionnée.';
        }

        if ($errorCode !== UPLOAD_ERR_OK) {
            return $fileName . ' (erreur upload)';
        }

        $fileSize = $file['size'] ?? 0;
        $fileType = $file['type'] ?? '';
        $tmpName = $file['tmp_name'] ?? '';

        if (!in_array($fileType, $allowedTypes)) {
            return $fileName . ' (format non autorisé)';
        }

        if ($fileSize > $maxFileSize) {
            return $fileName . ' (taille > ' . $maxFileSizeMb . ' Mo)';
        }

        if (!is_uploaded_file($tmpName)) {
            return $fileName . ' (fichier invalide)';
        }

        $uploadDir = __DIR__ . '/../../../public/uploads/articles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $uniqueName = 'article-' . $articleId . '-' . time() . '-' . uniqid() . '.' . $ext;
        $uploadPath = $uploadDir . $uniqueName;

        if (!move_uploaded_file($tmpName, $uploadPath)) {
            return $fileName . ' (impossible de sauvegarder)';
        }

        $this->optimizeImage($uploadPath, $fileType);

        $publicUrl = '/uploads/articles/' . $uniqueName;
        $altText = $articleTitle ?: $fileName;
        $this->newsModel->updateMedia($mediaId, $publicUrl, $altText);

        return true;
    }

    private function buildNewsShowResponse($news, $images, $error = null, $success = null) {
        $data = [
            'news' => $news,
            'images' => $images,
            'page_title' => 'Détail article - ' . htmlspecialchars($news['title'])
        ];

        if ($error) {
            $data['error'] = $error;
            $data['suppress_global_alert'] = true;
        }

        if ($success) {
            $data['success'] = $success;
            $data['suppress_global_alert'] = true;
        }

        return [
            'view' => 'back/news/show.php',
            'data' => $data
        ];
    }

    private function optimizeImage($filePath, $mimeType) {
        // Configuration d'optimisation
        $maxWidth = 1920;
        $maxHeight = 1080;
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

            // Calculer les nouvelles dimensions
            $ratio = min($maxWidth / $width, $maxHeight / $height, 1);
            if ($ratio < 1) {
                $newWidth = (int)($width * $ratio);
                $newHeight = (int)($height * $ratio);

                // Créer une nouvelle image redimensionnée
                $resized = @imagecreatetruecolor($newWidth, $newHeight);
                
                if (!$resized) {
                    @imagedestroy($image);
                    return;
                }

                // Préserver la transparence pour PNG et GIF
                if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
                    @imagealphablending($resized, false);
                    @imagesavealpha($resized, true);
                }

                // Redimensionner
                @imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                // Sauvegarder l'image optimisée
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

