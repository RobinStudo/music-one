SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `address` (`id`, `street`, `zipcode`, `city`, `country`) VALUES
                                                                         (19, '6 Pl. Maurice Schumann', '59000', 'Lille', 'FR'),
                                                                         (20, '29 Rue de Roubaix', '59000', 'Lille', 'FR'),
                                                                         (23, '12 rue du pont', '59000', 'Lille', 'FR'),
                                                                         (24, '12 rue du pont', '43000', 'Le Puy-en-Velay', 'FR');

INSERT INTO `booking` (`user_id`, `event_id`, `serial`, `seat`, `created_at`, `updated_at`, `payment_identifier`) VALUES
                                                                                                                      (11, 3, 0x6b7cd44629014517871ae856a023d106, 1, '2022-04-15 08:35:38', NULL, 'pi_3KoikQL0yFoQLPRb1bwTdtWQ'),
                                                                                                                      (13, 3, 0x80bdd910cfb14453a5c72db8ce8ea956, 2, '2022-02-10 11:29:39', NULL, NULL),
                                                                                                                      (13, 4, 0xf4b2b255d3ef44b8ad42fb83b3be50b0, 1, '2022-02-10 14:44:21', NULL, 'pi_3KRcw5L0yFoQLPRb1DqoS9HF');

INSERT INTO `category` (`id`, `name`, `icon`) VALUES
                                                  (52, 'Concert', 'fa-guitar'),
                                                  (53, 'Festival', 'fa-drum');

INSERT INTO `event` (`id`, `name`, `description`, `start_at`, `end_at`, `capacity`, `price`, `picture`, `category_id`, `place_id`, `owner_id`, `created_at`, `updated_at`) VALUES
                                                                                                                                                                               (3, 'Concert ACDC', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', '2021-12-10 19:30:00', '2021-12-10 23:30:00', 3000, 60, 'https://www.rollingstone.fr/wp-content/uploads/2020/11/acdcpower.jpeg', 52, 20, 12, '2022-02-08 11:01:00', NULL),
                                                                                                                                                                               (4, 'Concert Rolling Stones', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', '2022-12-15 19:30:00', '2022-12-15 23:30:00', 1000, 30, 'https://static.lpnt.fr/images/2020/07/22/20570001lpw-20570292-article-jpg_7246351_1250x625.jpg', 52, 20, 13, '2022-02-08 11:01:00', NULL),
                                                                                                                                                                               (8, 'Festival l\'accordéon', 'Découvrez les meilleurs groupes de la région', '2022-10-06 13:00:00', '2022-10-08 23:00:00', 150, 14, 'https://cdn.radiofrance.fr/s3/cruiser-production/2020/08/f3d13578-17c2-4138-a3ca-d3d8f9a825a1/870x489_maxnewsspecial141522.jpg', 53, 20, 13, '2022-04-15 08:55:05', NULL),
(9, 'Festival Rock&Rap', 'Découvrez les meilleurs groupes de Rock et de RAP', '2023-01-08 13:00:00', '2022-10-08 23:00:00', 2000, 90, 'https://images.ladepeche.fr/api/v1/images/view/609bcf4d3e45462ff41a87e6/large/image.jpg?v=1', 53, 19, 13, '2022-04-15 08:55:05', NULL),
(10, 'Concert Les p\'tits yeux', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', '2022-12-11 19:30:00', '2022-12-11 23:30:00', 50, 10, 'https://media-cdn.tripadvisor.com/media/photo-s/0b/3a/31/22/reste-au-bar.jpg', 52, 20, 13, '2022-02-08 11:01:00', NULL);

INSERT INTO `event_tag` (`event_id`, `tag_id`) VALUES
                                                   (4, 27),
                                                   (8, 27);

INSERT INTO `place` (`id`, `label`, `address_id`, `created_at`, `updated_at`) VALUES
                                                                                  (19, 'Au boudin bar', 19, '2022-02-08 11:01:00', NULL),
                                                                                  (20, 'Black Night', 20, '2022-02-08 11:01:00', NULL);

INSERT INTO `tag` (`id`, `name`) VALUES
                                     (27, 'Rock'),
                                     (28, 'Rap');

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `display_name`, `firstname`, `lastname`, `picture`, `status`, `address_id`, `created_at`, `updated_at`) VALUES
                                                                                                                                                                    (11, 'paul.h@gmail.com', '[]', '$2y$13$Fz6nuI5XHCD7jQ5ojDRcOOMvnddAtW0.iLfoGO22vLVp15IOZip1K', 'PaulH', 'Paul', 'Henderson', NULL, 'active', 24, '2022-02-08 11:01:00', '2022-04-15 08:35:15'),
                                                                                                                                                                    (12, 'jean.o@gmail.com', '[]', '$2y$13$oOO.CMf1mmFqzLgHKvCByuiAK/ucd7YPlKUowEo9jRTxHiIGcZ0p6', 'JeanO', NULL, NULL, NULL, 'active', NULL, '2022-02-08 11:01:00', NULL),
                                                                                                                                                                    (13, 'meco@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$tzbnxrXrbNdyNIaPoo8n7OJObzaV3nUl7ewpvjRSHgWtkkex5MSka', 'meco', 'Test', 'Test', NULL, 'active', 23, '2022-02-08 11:01:00', '2022-02-09 15:49:34'),
                                                                                                                                                                    (14, 'polo@dmail.com', '[]', '$2y$13$JVwkDNxe7InzFWKR5YotI.9wkgUNh4lxjh8ZlxbzMglT5gY1tvC3u', 'polo', NULL, NULL, NULL, 'active', NULL, '2022-02-08 11:09:16', NULL);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
