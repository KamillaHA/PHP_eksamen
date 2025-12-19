-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Vært: mariadb
-- Genereringstid: 19. 12 2025 kl. 18:07:25
-- Serverversion: 10.6.20-MariaDB-ubu2004
-- PHP-version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `company`
--

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `comments`
--

CREATE TABLE `comments` (
  `comment_pk` char(50) NOT NULL,
  `user_fk` char(50) NOT NULL,
  `post_fk` char(50) NOT NULL,
  `comment_text` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data dump for tabellen `comments`
--

INSERT INTO `comments` (`comment_pk`, `user_fk`, `post_fk`, `comment_text`, `created_at`, `updated_at`, `deleted_at`) VALUES
('0a6db118c5eabb7ad2d04f069a07901d3c0796329ffb6b0acf', '637666ef529161a67b0fdce85f61722c686dd33fa8580e5f6d', '59db8dd66ffb6e9687cad9764d93801a580395d004db6d7402', 'Hiiii', '2025-12-18 14:07:02', NULL, NULL),
('3833a9239d7c71a1d5327399a2a07716561e76105ed4476bfc', '637666ef529161a67b0fdce85f61722c686dd33fa8580e5f6d', '165d8bfe7a178d08c52c4e851b036cf44f7b83202c8983f767', 'hii', '2025-12-18 14:07:23', NULL, NULL),
('562433affd1324f738a71e78d7dcc41f0161ba39054d4ee4c9', '637666ef529161a67b0fdce85f61722c686dd33fa8580e5f6d', '36224a7c1e4457304093789fade1a66da3932cd422b2590bae', 'hi', '2025-12-18 14:08:19', NULL, NULL),
('c893b49b888136e8cf749386038044e29ce36af02b5ad60c21', '3ece34199faebcdfc254bfb6dadaa7e7f8e58b5b121544ab3f', '165d8bfe7a178d08c52c4e851b036cf44f7b83202c8983f767', 'hii', '2025-12-18 21:33:08', NULL, NULL),
('d2643ac547eba13abb9cea502221ea19393fa0145fbbc5a381', '4402441d108c745ee9f3c225186c68f7dadf50ea1c28090c6a', '165d8bfe7a178d08c52c4e851b036cf44f7b83202c8983f767', 'hellooooo', '2025-12-18 13:06:00', NULL, NULL),
('d4fade350bded168d05a53d4c7c71eb63451645416b70c9947', '3ece34199faebcdfc254bfb6dadaa7e7f8e58b5b121544ab3f', '59db8dd66ffb6e9687cad9764d93801a580395d004db6d7402', 'hi grace', '2025-12-18 21:20:48', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `follows`
--

CREATE TABLE `follows` (
  `follower_fk` char(50) NOT NULL,
  `following_fk` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data dump for tabellen `follows`
--

INSERT INTO `follows` (`follower_fk`, `following_fk`) VALUES
('3ece34199faebcdfc254bfb6dadaa7e7f8e58b5b121544ab3f', 'a00bfec909a1700dffc0e976b5220e813e3cbf8f0d11d68dc0'),
('637666ef529161a67b0fdce85f61722c686dd33fa8580e5f6d', '3ece34199faebcdfc254bfb6dadaa7e7f8e58b5b121544ab3f'),
('637666ef529161a67b0fdce85f61722c686dd33fa8580e5f6d', '4402441d108c745ee9f3c225186c68f7dadf50ea1c28090c6a');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `likes`
--

CREATE TABLE `likes` (
  `like_user_fk` char(50) NOT NULL,
  `like_post_fk` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data dump for tabellen `likes`
--

INSERT INTO `likes` (`like_user_fk`, `like_post_fk`) VALUES
('3ece34199faebcdfc254bfb6dadaa7e7f8e58b5b121544ab3f', '165d8bfe7a178d08c52c4e851b036cf44f7b83202c8983f767'),
('637666ef529161a67b0fdce85f61722c686dd33fa8580e5f6d', '165d8bfe7a178d08c52c4e851b036cf44f7b83202c8983f767');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `person`
--

CREATE TABLE `person` (
  `person_pk` bigint(20) UNSIGNED NOT NULL,
  `person_username` varchar(20) NOT NULL,
  `person_first_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `posts`
--

CREATE TABLE `posts` (
  `post_pk` char(50) NOT NULL,
  `post_message` varchar(200) NOT NULL,
  `post_image_path` varchar(255) DEFAULT NULL,
  `post_user_fk` char(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data dump for tabellen `posts`
--

INSERT INTO `posts` (`post_pk`, `post_message`, `post_image_path`, `post_user_fk`, `created_at`, `updated_at`, `deleted_at`) VALUES
('165d8bfe7a178d08c52c4e851b036cf44f7b83202c8983f767', 'hello?', 'https://picsum.photos/400/250', '4402441d108c745ee9f3c225186c68f7dadf50ea1c28090c6a', '2025-12-18 13:04:33', NULL, NULL),
('36224a7c1e4457304093789fade1a66da3932cd422b2590bae', 'hi againnnnnn', 'https://picsum.photos/400/250', '4402441d108c745ee9f3c225186c68f7dadf50ea1c28090c6a', '2025-12-18 13:15:46', NULL, NULL),
('48f6a4eb132a0fd1d816a600991b758d47f41297ad0a263829', 'Hiii, hope y´all have a lovely Thursday', 'https://picsum.photos/400/250', '4402441d108c745ee9f3c225186c68f7dadf50ea1c28090c6a', '2025-12-18 12:57:02', NULL, NULL),
('529ba48be85f4217c9c085d91a315a43f35b4a108d367db760', 'I´m new - what´s happening? :)', 'https://picsum.photos/400/250', '4402441d108c745ee9f3c225186c68f7dadf50ea1c28090c6a', '2025-12-18 12:51:27', NULL, NULL),
('59db8dd66ffb6e9687cad9764d93801a580395d004db6d7402', 'Is this the new Twitter?', 'https://picsum.photos/400/250', '3ece34199faebcdfc254bfb6dadaa7e7f8e58b5b121544ab3f', '2025-12-18 12:20:05', NULL, NULL),
('5b3da6ce94309b28765339f88f1310008e304adeb9ebae6c77', 'nyt billede', '/uploads/be364a59fb75a35ac723a295cd465777.jpg', '3ece34199faebcdfc254bfb6dadaa7e7f8e58b5b121544ab3f', '2025-12-18 22:35:47', NULL, NULL),
('621e89e5bed9b28fafa52d5fc415df084cce1cc31bf168ca94', 'I wanna find friends in here..', 'https://picsum.photos/400/250', '4402441d108c745ee9f3c225186c68f7dadf50ea1c28090c6a', '2025-12-18 13:02:48', NULL, NULL),
('67db5c67242625b31cd853250992058a3c2116eed1c75aba91', 'works?', '/uploads/6cec446697e804fcb9ab0f5d8580780a.jpg', '3ece34199faebcdfc254bfb6dadaa7e7f8e58b5b121544ab3f', '2025-12-18 22:16:14', NULL, NULL),
('7ec2b3d158a137ee583a7649781d23df2252d18d1c5a386e62', 'hi', NULL, '3ece34199faebcdfc254bfb6dadaa7e7f8e58b5b121544ab3f', '2025-12-19 09:19:45', NULL, NULL),
('b9d18ee3381debca916219cf0d6a239b20eb09178b070bf5ac', 'hiiiiiii its me', NULL, '3ece34199faebcdfc254bfb6dadaa7e7f8e58b5b121544ab3f', '2025-12-18 22:10:05', NULL, NULL),
('dcc8ab456635f75b73dfa5cbb32277f76dd112f5103fea0998', 'hiii', 'https://picsum.photos/400/250', '4402441d108c745ee9f3c225186c68f7dadf50ea1c28090c6a', '2025-12-18 13:15:37', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `users`
--

CREATE TABLE `users` (
  `user_pk` char(50) NOT NULL,
  `user_username` varchar(20) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_full_name` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  `user_cover_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data dump for tabellen `users`
--

INSERT INTO `users` (`user_pk`, `user_username`, `user_email`, `user_password`, `user_full_name`, `created_at`, `updated_at`, `deleted_at`, `user_cover_image`) VALUES
('3ece34199faebcdfc254bfb6dadaa7e7f8e58b5b121544ab3f', 'kamillahuhnke', 'k@k.com', '$2y$10$eARR9V0F/.0RmNaotrMg6.mQ5qS.qulp9tNWWsHE6qxvXRwRtt/3C', 'Kamilla', '2025-12-18 12:19:35', '2025-12-19 10:36:35', NULL, NULL),
('4402441d108c745ee9f3c225186c68f7dadf50ea1c28090c6a', 'madeleine', 'm@m.com', '$2y$10$fSOY/OiKuKQ8j4wVmi5SZuFGAXLnZobe3p3afrUej83holHzpyIOe', 'Madeleine', '2025-12-18 12:21:44', '2025-12-18 21:36:46', NULL, NULL),
('637666ef529161a67b0fdce85f61722c686dd33fa8580e5f6d', 'gracehopper', 'g@h.com', '$2y$10$LuTgoidyWL2XWUMuI7H7rOk9m6VbIUfqbJmIm9/zttSwP3.CDCImy', 'Grace', '2025-12-18 13:18:16', NULL, NULL, NULL),
('a00bfec909a1700dffc0e976b5220e813e3cbf8f0d11d68dc0', 'stevejobs', 's@j.com', '$2y$10$kBm2GXsoOAJ4at0cw48UWOGU1SLT123IcLIjAj1eYVogK4jaaT8ka', 'Steve', '2025-12-18 13:17:51', NULL, NULL, NULL);

--
-- Begrænsninger for dumpede tabeller
--

--
-- Indeks for tabel `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_pk`),
  ADD KEY `post_fk` (`post_fk`),
  ADD KEY `user_fk` (`user_fk`),
  ADD KEY `idx_comments_deleted_at` (`deleted_at`);

--
-- Indeks for tabel `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`follower_fk`,`following_fk`),
  ADD KEY `following_fk` (`following_fk`);

--
-- Indeks for tabel `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_user_fk`,`like_post_fk`),
  ADD KEY `like_post_fk` (`like_post_fk`);

--
-- Indeks for tabel `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`person_pk`),
  ADD UNIQUE KEY `person_pk` (`person_pk`),
  ADD UNIQUE KEY `person_username` (`person_username`);

--
-- Indeks for tabel `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_pk`),
  ADD KEY `idx_posts_deleted_at` (`deleted_at`);

--
-- Indeks for tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_pk`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD UNIQUE KEY `user_pk` (`user_pk`),
  ADD KEY `idx_users_deleted_at` (`deleted_at`);

--
-- Brug ikke AUTO_INCREMENT for slettede tabeller
--

--
-- Tilføj AUTO_INCREMENT i tabel `person`
--
ALTER TABLE `person`
  MODIFY `person_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Begrænsninger for dumpede tabeller
--

--
-- Begrænsninger for tabel `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_fk`) REFERENCES `posts` (`post_pk`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_fk`) REFERENCES `users` (`user_pk`) ON DELETE CASCADE;

--
-- Begrænsninger for tabel `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`follower_fk`) REFERENCES `users` (`user_pk`) ON DELETE CASCADE,
  ADD CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`following_fk`) REFERENCES `users` (`user_pk`) ON DELETE CASCADE;

--
-- Begrænsninger for tabel `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`like_post_fk`) REFERENCES `posts` (`post_pk`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`like_user_fk`) REFERENCES `users` (`user_pk`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
