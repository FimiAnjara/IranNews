SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;
SET CHARACTER SET utf8mb4;
USE irannews;

SET FOREIGN_KEY_CHECKS = 0;

-- DROP tables en bon ordre (FK d'abord)
DROP TABLE IF EXISTS media;
DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

-- CREATE TABLE users
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CREATE TABLE categories
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  slug VARCHAR(150) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CREATE TABLE articles
CREATE TABLE articles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  content LONGTEXT NOT NULL,
  autor VARCHAR(100),
  etat INT DEFAULT 0,
  published_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  delete_at TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT fk_articles_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CREATE TABLE media
CREATE TABLE media (
  id INT AUTO_INCREMENT PRIMARY KEY,
  article_id INT NOT NULL,
  url VARCHAR(255) NOT NULL,
  alt_text VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  delete_at TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT fk_media_article FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- INSERT users d'abord
INSERT INTO users (id, name, email, password_hash)
VALUES (1, 'Admin', 'admin@example.com', '$2y$10$7ZINO38uYLWmUOw1.gCAc.yI6O7UR79mMj2v3Tys1CzFl6Gqdxt2O');

-- INSERT categories AVEC IDS EXPLICITES
INSERT INTO categories (id, name, slug) VALUES
(1, 'Actualités', 'actualites'),
(2, 'Analyses', 'analyses'),
(3, 'Chronologie', 'chronologie'),
(4, 'Géopolitique', 'geopolitique'),
(5, 'Diplomatie', 'diplomatie'),
(6, 'Contexte Historique', 'contexte-historique');

-- Réactiver les FK APRÈS insertion des tables parent
SET FOREIGN_KEY_CHECKS = 1;

-- INSERT articles
INSERT INTO articles (category_id, title, slug, description, content, autor, etat, published_at)
VALUES
(1, 'Un mois de guerre : les enjeux du conflit Iran-États-Unis et Israël', 'un-mois-guerre-iran-enjeux', 'Après un mois d\'attaques intensives, le conflit transforme la géopolitique mondiale. Analyse des principaux changements régionaux et impacts économiques.', '<p>Depuis le début du conflit armé il y a un mois, les tensions au Moyen-Orient ont atteint un niveau sans précédent. Les attaques coordonnées entre les États-Unis et Israël contre l\'Iran marquent un tournant majeur dans la région.</p><p>Les conséquences humanitaires sont considérables : plus de 1 900 morts en Iran, estimations croissantes des pertes civiles, et une infrastructure critiquement endommagée. Les observateurs internationaux alertent sur les risques d\'escalade régionale.</p><p>Le conflit a également des répercussions économiques globales, particulièrement sur les marchés pétroliers et les chaînes d\'approvisionnement mondiales. Le Détroit d\'Ormuz demeure une zone hautement stratégique et volatile.</p>', 'Jean Martin', 1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, 'Analyse : Comment la guerre façonne la géopolitique mondiale', 'analyse-guerre-geopolitique-mondiale', 'Experts et analystes examinent les conséquences structurelles du conflit sur l\'ordre international.', '<p>La guerre en Iran redéfinit les alignements géopolitiques mondiaux. Les alliés traditionnels des États-Unis reconsidèrent leurs positions, tandis que d\'autres puissances exploitent l\'instabilité régionale.</p><p>La Chine surveille attentivement la situation dans le contexte de sa rivalité avec Washington. La Russie, engagée en Ukraine, observe les annonces diplomatiques avec attention. L\'Europe débat de ses responsabilités au Moyen-Orient.</p><p>Les monarchies du Golfe se trouvent dans une position délicate, tiraillées entre leurs alliances historiques et leurs intérêts commerciaux.</p>', 'Sarah Chen', 1, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(3, 'Chronologie détaillée : comment le conflit a escaladé', 'chronologie-escalade-conflit', 'Retracez les événements clés qui ont mené au conflit armé ouvert.', '<p><strong>Février 2026 :</strong> Tensions accrues suite à des déclarations rhétoriques. Les États-Unis déploient des navires supplémentaires.</p><p><strong>Début mars :</strong> Premier incident impliquant une frappe contre une installation militaire. Appels à la retenue de la communauté internationale.</p><p><strong>15 mars :</strong> Riposte iranienne. Les combats s\'intensifient. Premiers appels humanitaires.</p><p><strong>20 mars :</strong> Raid aérien majeur. Première vague de pertes civiles signalées. Fermeture du Détroit d\'Ormuz annoncée par l\'Iran.</p><p><strong>Fin mars :</strong> Situation actuelle. Pourparlers diplomatiques en cours. Craintes d\'escalade régionale.</p>', 'Marc Dubois', 1, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(4, 'Strait of Hormuz : pourquoi ce débouché est crucial pour l\'économie mondiale', 'detroit-hormuz-crise-economique', 'Le contrôle du détroit stratégique menace les approvisionnements énergétiques mondiaux.', '<p>Le Détroit d\'Ormuz constitue l\'une des voies navigables les plus critiques au monde. Environ 30 % du pétrole transporté par mer transite par ce passage étroit entre l\'Iran et Oman.</p><p>L\'Iran a fermé le détroit aux navires commerciaux, déclenchant des craintes de pénurie énergétique mondiale. Les prix du pétrole ont bondi, affectant l\'économie globale.</p><p>Les États-Unis ont indiqué leur capacité à maintenir la route ouverte, mais les risques militaires demeurent élevés. Cette situation crée une incertitude majeure pour les investisseurs et les économies dépendantes du pétrole du Golfe.</p>', 'Paul Leclerc', 1, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(5, 'Trump déclare vouloir terminer la guerre en quelques semaines', 'trump-fin-guerre-quelques-semaines', 'Le président américain envoie des signaux mitigés sur la durée du conflit.', '<p>Le président Trump a déclaré publiquement vouloir terminer le conflit en quelques semaines, promettant une victoire rapide sur l\'Iran.</p><p>Cependant, ses déclarations contredisent parfois ses envoyés spéciaux qui évoquent des négociations plus laborieuses. Cette ambiguïté crée de l\'incertitude sur les véritables objectifs militaires américains.</p><p>Les experts considèrent une résolution rapide comme peu probable, compte tenu de la complexité géopolitique et des positions irréconciliables de chaque partie.</p>', 'Robert Morrison', 1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(6, 'Iran : comment le régime consolide son influence régionale malgré la guerre', 'iran-influence-regionale-guerra', 'Malgré les bombardements, l\'Iran renforce ses alliances avec la Syrie, l\'Irak et les Houthis du Yémen.', '<p>Paradoxalement, le conflit armé permet à l\'Iran de consolider son leadership régional auprès de ses alliés. Les Houthis du Yémen atacan Israël pour la première fois directement, montrant la portée du réseau iranien.</p><p>Les forces liées à l\'Iran restent mobilisées et fonctionnelles. Les représentants iraniens maintiennent un discours de résilience et de résistance, renforçant le soutien interne.</p><p>Certains observateurs suggèrent que cette position pourrait renforcer le régime plutôt que le menacer, au moins court terme.</p>', 'Fatima Hassan', 1, DATE_SUB(NOW(), INTERVAL 6 DAY)),
(4, 'Rapport : la Maison Blanche accuse des pirates informatiques liés à l\'Iran d\'intrusion au FBI', 'iran-cyberattaque-fbi-dossiers', 'Des pirates ont accédé aux emails personnels du directeur du FBI et publié des documents confidentiels.', '<p>Un groupe de hackers prétendument lié à l\'Iran a revendiqué une intrusion majeure dans les systèmes du FBI, exposant des emails personnels du directeur Kash Patel.</p><p>Bien que les autorités déclarent que les documents sont anciens et historiques, l\'incident illustre les capacités de cyberguerre de l\'Iran.</p><p>Cet événement ajoute une dimension supplémentaire au conflit, dépassant le domaine purement militaire pour inclure la guerre informatique.</p>', 'Lisa Anderson', 1, DATE_SUB(NOW(), INTERVAL 7 DAY)),
(1, 'Les alliés de l\'OTAN divisés sur le soutien à la guerre en Iran', 'otan-divisee-soutien-guerre', 'Les États-membres de l\'OTAN présentent des positions divergentes face aux opérations militaires en Iran.', '<p>Malgré les appels américains à l\'unité, les pays européens affichent une prudence croissante face au conflit. Certains refusent toute participation directe, tandis que d\'autres maintiennent un soutien diplomatique mesuré.</p><p>La France, l\'Allemagne et d\'autres nations s\'inquiètent des conséquences à long terme. Les protestations internes s\'amplifient, particulièrement chez les jeunes électeurs.</p><p>Trump critique ouvertement ce qu\'il perçoit comme un manque de solidarité de l\'OTAN, augmentant les tensions transatlantiques.</p>', 'Michel Fournier', 1, DATE_SUB(NOW(), INTERVAL 8 DAY)),
(6, 'Histoire : les origines de la tension irano-américaine depuis 1979', 'histoire-tensions-iran-usa-1979', 'Comprendre les racines profondes du conflit actuel dans le contexte historique.', '<p>Le conflit actuel ne date pas d\'hier. Les relations irano-américaines se sont dégradées depuis la révolution iranienne de 1979 et l\'arrivée au pouvoir des mollahs.</p><p>Les points de friction historiques incluent la crise des otages de 1979-1980, les sanctions américaines répétées, les accusations de programmes nucléaires militaires, et les interventions régionales.</p><p>Comprendre cette histoire est essentiel pour saisir les enjeux actuels et les positions irréconciliables de chaque camp.</p>', 'Dr. Hassan Khalil', 1, DATE_SUB(NOW(), INTERVAL 9 DAY)),
(2, 'Économie mondiale : impact du choc pétrolier sur les prix de l\'énergie', 'choc-petrolier-prix-energie', 'Les marchés énergétiques subissent une volatilité extrême due au conflit régional.', '<p>Le prix du brut a dépassé les 150 dollars le baril, niveau non vu depuis des années. Cette envolée affecte directement les consommateurs, les industries et l\'inflation globale.</p><p>Les économies fortement dépendantes des importations pétrolières subissent les plus grandes pressions. Des pays émergents voient leurs réserves de change s\'épuiser.</p><p>Les analystes s\'attendent à une persistance de cette volatilité tant que le conflit demeure irrésolu.</p>', 'Économiste Antoine Lefevre', 1, DATE_SUB(NOW(), INTERVAL 10 DAY));