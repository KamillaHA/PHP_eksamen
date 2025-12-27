DROP DATABASE IF EXISTS company;
CREATE DATABASE company
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE company;

-- =====================================================
-- USERS
-- =====================================================
CREATE TABLE users (
  user_pk CHAR(50) NOT NULL,
  user_username VARCHAR(20) NOT NULL,
  user_email VARCHAR(100) NOT NULL,
  user_password VARCHAR(255) NOT NULL,
  user_full_name VARCHAR(20) NOT NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME DEFAULT NULL,

  user_cover_image VARCHAR(255) DEFAULT NULL,

  PRIMARY KEY (user_pk),
  UNIQUE KEY user_email (user_email),
  KEY idx_users_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- POSTS
-- =====================================================
CREATE TABLE posts (
  post_pk CHAR(50) NOT NULL,
  post_message VARCHAR(300) NOT NULL,
  post_image_path VARCHAR(255) DEFAULT NULL,
  post_user_fk CHAR(50) NOT NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME DEFAULT NULL,

  PRIMARY KEY (post_pk),
  KEY idx_posts_deleted_at (deleted_at),
  KEY post_user_fk (post_user_fk),

  CONSTRAINT posts_ibfk_1
    FOREIGN KEY (post_user_fk)
    REFERENCES users (user_pk)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- COMMENTS
-- =====================================================
CREATE TABLE comments (
  comment_pk CHAR(50) NOT NULL,
  user_fk CHAR(50) NOT NULL,
  post_fk CHAR(50) NOT NULL,
  comment_text VARCHAR(255) NOT NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME DEFAULT NULL,

  PRIMARY KEY (comment_pk),
  KEY idx_comments_deleted_at (deleted_at),
  KEY post_fk (post_fk),
  KEY user_fk (user_fk),

  CONSTRAINT comments_ibfk_1
    FOREIGN KEY (post_fk)
    REFERENCES posts (post_pk)
    ON DELETE CASCADE,

  CONSTRAINT comments_ibfk_2
    FOREIGN KEY (user_fk)
    REFERENCES users (user_pk)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- LIKES
-- (matcher: like_user_fk, like_post_fk)
-- =====================================================
CREATE TABLE likes (
  like_user_fk CHAR(50) NOT NULL,
  like_post_fk CHAR(50) NOT NULL,

  PRIMARY KEY (like_user_fk, like_post_fk),
  KEY like_post_fk (like_post_fk),

  CONSTRAINT likes_ibfk_1
    FOREIGN KEY (like_user_fk)
    REFERENCES users (user_pk)
    ON DELETE CASCADE,

  CONSTRAINT likes_ibfk_2
    FOREIGN KEY (like_post_fk)
    REFERENCES posts (post_pk)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- FOLLOWS
-- (matcher: follower_fk, following_fk)
-- =====================================================
CREATE TABLE follows (
  follower_fk CHAR(50) NOT NULL,
  following_fk CHAR(50) NOT NULL,

  PRIMARY KEY (follower_fk, following_fk),
  KEY following_fk (following_fk),

  CONSTRAINT follows_ibfk_1
    FOREIGN KEY (follower_fk)
    REFERENCES users (user_pk)
    ON DELETE CASCADE,

  CONSTRAINT follows_ibfk_2
    FOREIGN KEY (following_fk)
    REFERENCES users (user_pk)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
