-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2024 at 02:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hair4u`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_page`
--

CREATE TABLE `about_page` (
  `id` int(11) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `body_text` text NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_page`
--

INSERT INTO `about_page` (`id`, `heading`, `body_text`, `image_path`) VALUES
(1, 'Meet Melissa', 'Melissa is an internationally experienced, qualified senior stylist. She has been working in the hairdressing industry for  over 30 years.  Melissa aquired Hair 4 U in 2019 and has a wealth of experience serving the Rangitīkei District, having owned and operated Dejavu Hair by Design  in Marton from 2004-2015.  Melissa is also a qualified Hairdressing Lecturer and Assessor and works in this role additionally part time.', 'about/melissa.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `ad_1` varchar(50) NOT NULL,
  `activation_code` varchar(50) DEFAULT '',
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `firstname`, `lastname`, `username`, `password`, `email`, `phone`, `ad_1`, `activation_code`, `role`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'Melissa', 'True', 'admin', '$2y$10$SfhYIDtn.iOuCW7zfoFLuuZHX6lja4lF4XA4JqNmpiH/.P3zB8JCa', 'test@test.com', '', '', '', 'admin', NULL, NULL),
(2, 'Dominic', 'True', 'domtrue', '$2y$10$tiSEn2bWeJN6Irwqyku8QuguP0c8KWvZ1WnWO6EX0cxDaUBPNhQRy', 'domtrue.dt@icloud.com', '123456789', '', '0d331c851c332d80638eedaf35e90b18', 'customer', '5ea235ae34b9618f42a49b2dbfc2cf6e', '2024-12-16 03:42:30');

-- --------------------------------------------------------

--
-- Table structure for table `admin_images`
--

CREATE TABLE `admin_images` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_images`
--

INSERT INTO `admin_images` (`id`, `account_id`, `image_path`) VALUES
(1, 1, 'img/about/melissa.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `customer_first_name` varchar(50) NOT NULL,
  `customer_last_name` varchar(50) NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `appointment_datetime` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `customer_first_name`, `customer_last_name`, `service_type`, `appointment_datetime`, `created_at`) VALUES
(1, 'Dominic', 'True', 'Perm', '2024-11-12 10:30:00', '2024-11-05 05:46:15'),
(2, 'Dominic', 'True', 'Haircut', '2024-12-18 14:00:00', '2024-12-11 05:30:05');

-- --------------------------------------------------------

--
-- Table structure for table `business_logo`
--

CREATE TABLE `business_logo` (
  `id` int(11) NOT NULL,
  `logo_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_logo`
--

INSERT INTO `business_logo` (`id`, `logo_path`) VALUES
(1, 'img/logo.png'),
(2, 'img/logo_white.png');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `firstname`, `lastname`, `email`, `message`, `created_at`) VALUES
(1, '', '', '', 'hi :)', '2024-09-22 14:58:44');

-- --------------------------------------------------------

--
-- Table structure for table `hair_services`
--

CREATE TABLE `hair_services` (
  `id` int(11) NOT NULL,
  `content_type` enum('paragraph','list_item') NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hair_services`
--

INSERT INTO `hair_services` (`id`, `content_type`, `content`) VALUES
(1, 'paragraph', 'Hair 4 U provides a wide range of professional hair services tailored to meet each customer’s unique needs. From trendy cuts and vibrant colouring to expert styling and treatments, the salon is dedicated to ensuring every client leaves with a look they love. With a focus on quality and customer satisfaction, Hair 4 U combines creativity with expertise to deliver the best hairstyles for every occasion. Find a list of each service that Melissa provides below:'),
(2, 'list_item', 'Haircuts'),
(3, 'list_item', 'Colours'),
(4, 'list_item', 'Foils'),
(5, 'list_item', 'Perms'),
(6, 'list_item', 'Treatments'),
(7, 'list_item', 'Blow waves');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `status` enum('Pending','Completed') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `grand_total`, `status`, `created_at`) VALUES
(7, 2, 68.00, 'Pending', '2024-12-10 21:48:37'),
(8, 2, 68.00, 'Pending', '2024-12-10 21:53:06'),
(9, 2, 68.00, 'Pending', '2024-12-10 21:57:49'),
(10, 2, 68.00, 'Pending', '2024-12-10 21:59:02'),
(11, 2, 68.00, 'Pending', '2024-12-10 22:00:14'),
(12, 2, 68.00, 'Pending', '2024-12-10 22:05:48'),
(13, 2, 68.00, 'Pending', '2024-12-10 22:08:23'),
(14, 2, 68.00, 'Pending', '2024-12-10 22:08:53'),
(15, 2, 68.00, 'Pending', '2024-12-10 22:09:08'),
(16, 2, 38.50, 'Pending', '2024-12-10 23:03:42');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`, `subtotal`) VALUES
(1, 12, 2, 1, 29.50, 29.50),
(2, 12, 1, 1, 29.50, 29.50),
(3, 13, 2, 1, 29.50, 29.50),
(4, 13, 1, 1, 29.50, 29.50),
(5, 14, 2, 1, 29.50, 29.50),
(6, 14, 1, 1, 29.50, 29.50),
(7, 15, 2, 1, 29.50, 29.50),
(8, 15, 1, 1, 29.50, 29.50),
(9, 16, 1, 1, 29.50, 29.50);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title1` varchar(255) DEFAULT NULL,
  `text1` text DEFAULT NULL,
  `image1` varchar(255) DEFAULT NULL,
  `title2` varchar(255) DEFAULT NULL,
  `text2` text DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `title3` varchar(255) DEFAULT NULL,
  `text3` text DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title1`, `text1`, `image1`, `title2`, `text2`, `image2`, `title3`, `text3`, `image3`) VALUES
(1, 'Welcome to Hair 4 U', 'Where style meets sophistication in the heart of Bulls, New Zealand. Owner and senior stylist Melissa True brings her passion and expertise to every appointment. Whether you\'re looking for a fresh new look or a classic cut, Melissa is dedicated to delivering the perfect style for your needs. ', NULL, NULL, 'Looking for top-notch hair products? Explore our curated selection of quality items from industry-leading brands, all available for purchase online at competitive prices. ', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_details`
--

CREATE TABLE `payment_details` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `card_name` varchar(255) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `payment_method_id` varchar(255) NOT NULL,
  `status` enum('Success','Failed') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `image`, `price`) VALUES
(1, 'De Lorenzo Rejuven8 Shampoo 375mL', 'Extends the life of Coloured Hair. A mild cleanser with a rich lather that maximises colour vibrancy and provides softening and moisture to hair. \r\n\r\nContains Baobab Protein, Gingko Biloba and Berry extracts.', 'products\\de_lorenzo\\instant\\rejuven8_shampoo_375ml.png', 29.50),
(2, 'De Lorenzo Rejuven8 Conditioner 375mL', 'Extends the life of Coloured Hair. A rich formulation that replenishes lost moisture, maximizes colour vibrancy and provides exceptional detangling of colour treated hair. \r\n\r\nContains Baobab Protein, Ginkgo Biloba and Berry extracts.', 'products\\de_lorenzo\\instant\\rejuven8_conditioner_375ml.png', 29.50),
(3, 'De Lorenzo Rejuven8 The Ends 120mL', 'A unique blend of shine and sealing actives that work together to moisturise and prevent split ends. The result is long-lasting smooth, polished hair. Suitable for all hair types.\r\n\r\nContains Bilberry and Blackberry extracts.', 'products/de_lorenzo/instant/rejuven8_the_ends_120ml.png', 28.00),
(4, 'De Lorenzo Rejuven8 Treatment 150g', 'A deep conditioning, colour safe treatment that protects hair against colour fade and chemical damage, leaving hair moisturised with a smooth, silky finish.\r\n\r\nContains Ginkgo Biloba, Bilberry and Blueberry extracts.', 'products/de_lorenzo/instant/rejuven8_treatment_150g.png', 29.00),
(5, 'De Lorenzo Allevi8 Shampoo 375mL', 'Smooths Dry and Unruly Hair.\r\n\r\nA rich anti-frizz formula that instantly cleanses and smooths normal to dry hair while softening and moisturising, leaving the scalp purified and refreshed.\r\n\r\nContains Baobab Protein, Kakadu Plum and Cactus Flower.', 'products/de_lorenzo/instant/allevi8_shampoo_375ml.png', 29.50),
(6, 'De Lorenzo Allevi8 Conditioner 375mL', 'Smooths Dry and Unruly Hair.\r\n\r\nA smoothing conditioner containing specialised ingredients to nourish and relieve frizz, leaving hair soft with healthy shine and a sleek finish.\r\n\r\nContains Baobab Protein and Desert extracts.', 'products/de_lorenzo/instant/allevi8_conditioner_375ml.png', 29.50),
(7, 'De Lorenzo Allevi8 Shine Serum 30mL', 'A leave-in polish with a humidity resistant formula that smooths and eliminates frizz and fly-aways for a glossy, controlled finish. Suitable for all hair types.\r\n\r\nContains Avocado Oil and Apricot Oil.', 'products/de_lorenzo/instant/allevi8_shine_serum_30ml.png', 26.00),
(8, 'De Lorenzo Accentu8 Shampoo 375mL', 'Volumising for Fine Hair.\r\n\r\nGently cleanses and invigorates fine hair with an organic body building formula, leaving it with brilliant volume and shine.\r\n\r\nContains Baobab Protein, Seaweed and Ginseng.', 'products/de_lorenzo/instant/accentu8_shampoo_375ml.png', 29.50),
(9, 'De Lorenzo Accentu8 Conditioner 375mL', 'Volumising for Fine Hair.\r\n\r\nProvides lightweight conditioning and invigorates fine hair while delivering a veil of film formers to thicken hair for brilliant volume and shine.\r\n\r\nContains Baobab Protein, Seaweed and Ginseng.', 'products/de_lorenzo/instant/accentu8_conditioner_375ml.png', 29.50),
(10, 'De Lorenzo Instant Satur8 125g', 'A hydrating foam that revitalises and detangles. Provides daily protection from the natural elements, improves softness and manageability. Suitable for all hair types.\r\n\r\nContains Baobab Protein, Cucumber and Aloe Vera extracts.', 'products/de_lorenzo/instant/instant_satur8.png', 27.00),
(11, 'De Lorenzo Restructurant 200mL', 'A weightless leave-in treatment that delivers 8 superior benefits to all hair types. Provides strength, shine, moisture, smoothness, conditioning, UV sun protection, colour protection and detanglability. This self regulating product ensures hair absorbs the active care ingredients according to need.\r\n\r\nContains botanically based Protein Complex.', 'products/de_lorenzo/instant/instant_restructurant_200ml.png', 30.00),
(12, 'De Lorenzo Illumin8 Shampoo 375mL', 'Brightens and Tones Blonde Hair.\r\n\r\nTone blonde hair in an instant. Instantly cleanse your blonde and pre-lightened hair to neutralise unwanted yellow tones with our concentrated blue-violet pigment-infused formula.\r\n\r\nContains Baobab Protein, Chamomile and Wine Extracts to enhance gloss, provide UV sun protection and brighten blondes.\r\n\r\nIt is recommended to wear gloves as temporary staining of hands can occur due to the strong deposit of colour.', 'products/de_lorenzo/instant/illumin8_shampoo_375ml.png', 29.50),
(13, 'De Lorenzo Illumin8 Conditioner 375mL', 'Brightens and Tones Blonde Hair.\r\n\r\nTone blonde hair in an instant. Instantly tones your blonde hair with blue-violet pigments and softens to leave hair nourished and hydrated.\r\n\r\nContains Baobab Protein, Chamomile and exotic Wine Extracts to enhance gloss and brighten blondes. Provides UV sun protection.', 'products/de_lorenzo/instant/illumin8_conditioner_375ml.png', 29.50),
(14, 'De Lorenzo Extinguish 200mL', 'This unique and best selling thermal spray shields hair from breakage and split ends.\r\n\r\nIt is essential for protecting hair against heat damage and providing humidity resistant, straight, smooth hair (when using straightening irons). Protects up to 240°C.\r\n\r\nContains Argan Oil.', 'products/de_lorenzo/bond_defence/defence_extinguish_200ml.png', 31.90),
(15, 'De Lorenzo Argan Oil 50mL', 'A long lasting finishing oil that replenishes dry, brittle hair and eliminates frizz.\r\n\r\nInfused with Organic Argan Oil, it contains specialised elastomers leaving the hair silky, conditioned and with diamond shine.', 'products/de_lorenzo/bond_defence/argan_oil_50ml.png', 34.90),
(16, 'De Lorenzo Silver Shampoo 250mL', 'Reduce unwanted brassy yellow tones in blonde hair.\r\n\r\nSpecifically formulated using a combination of both paraben free preservatives and a sulfate free cleansing system derived from coconut fatty acids. Botanical extracts of Australian Kakadu Plum and Birch gently cleanse the hair and enhance colour, while the organic Rooibos extract helps to protect the hair against UV sun rays.', 'products/de_lorenzo/novafusion/silver_shampoo_250ml.png', 31.50),
(17, 'De Lorenzo Silver Conditioner 250mL', 'Locks in colour to keep blondes looking toned.\r\n\r\nSpecifically formulated using a combination of both paraben free preservatives and a sulfate free cleansing system derived from coconut fatty acids. Botanical extracts of Australian Kakadu Plum and Birch gently cleanse the hair and enhance colour, while the organic Rooibos extract helps to protect the hair against UV sun rays.\r\n\r\nFor best results, use in conjunction with the Silver Shampoo.', 'products/de_lorenzo/novafusion/silver_conditioner_250ml.png', 31.50),
(18, 'De Lorenzo Rosewood Shampoo 250mL', 'Provides violet highlights and pastel rosewood tones to blonde hair.\r\n\r\nSpecifically formulated using a combination of both paraben free preservatives and a sulfate free cleansing system derived from coconut fatty acids. Botanical extracts of Australian Kakadu Plum and Birch gently cleanse the hair and enhance colour, while the organic Rooibos extract helps to protect the hair against UV sun rays.', 'products/de_lorenzo/novafusion/rosewood_250ml.png', 31.50),
(19, 'De Lorenzo Coral Peach Shampoo 250mL', 'Provides peach highlights and coral peach tones to blonde hair.\r\n\r\nSpecifically formulated using a combination of both paraben free preservatives and a sulfate free cleansing system derived from coconut fatty acids. Botanical extracts of Australian Kakadu Plum and Birch gently cleanse the hair and enhance colour, while the organic Rooibos extract helps to protect the hair against UV sun rays.', 'products/de_lorenzo/novafusion/coral_peach_250ml.png', 31.50),
(20, 'De Lorenzo Rose Gold Shampoo 250mL', 'Provides rose gold tones to blonde hair.\r\n\r\nSpecifically formulated using a combination of both paraben free preservatives and a sulfate free cleansing system derived from coconut fatty acids. Botanical extracts of Australian Kakadu Plum and Birch gently cleanse the hair and enhance colour, while the organic Rooibos extract helps to protect the hair against UV sun rays.', 'products/de_lorenzo/novafusion/rose_gold_250ml.png', 31.50),
(21, 'De Lorenzo Cherry Red Shampoo 250mL', 'Enhances vibrant red-violet tones in coloured or natural hair.\r\n\r\nSpecifically formulated using a combination of both paraben free preservatives and a sulfate free cleansing system derived from coconut fatty acids. Botanical extracts of Australian Kakadu Plum and Birch gently cleanse the hair and enhance colour, while the organic Rooibos extract helps to protect the hair against UV sun rays.', 'products/de_lorenzo/novafusion/cherry_red_250ml.png', 31.50),
(22, 'De Lorenzo Copper Shampoo 250mL', 'Enhances vibrant copper tones in coloured or natural hair.\r\n\r\nSpecifically formulated using a combination of both paraben free preservatives and a sulfate free cleansing system derived from coconut fatty acids. Botanical extracts of Australian Kakadu Plum and Birch gently cleanse the hair and enhance colour, while the organic Rooibos extract helps to protect the hair against UV sun rays.', 'products/de_lorenzo/novafusion/copper_250ml.png', 31.50),
(23, 'De Lorenzo Intense Ruby Red Shampoo 200mL', 'Ideal for highlighting redheads. Adds powerful deep red-violet tones to medium-dark brown hair. The unique dual action of temporary colour with semi-permanent action allows for exceptional colour uptake, even results and longevity.\r\n\r\nSpecifically formulated using a combination of both paraben free preservatives and a sulfate free cleansing system derived from coconut fatty acids. Botanical extracts of Australian Kakadu Plum and Birch gently cleanse the hair and enhance colour, while the organic Rooibos extract helps to protect the hair against UV sun rays.', 'products/de_lorenzo/novafusion/intense_ruby_red_200ml.png', 31.50),
(24, 'De Lorenzo Novafusion Conditioner 250mL', 'Enhances vibrancy and brilliance of coloured hair.\r\n\r\nThe conditioner locks in and prevents colour fade resulting in a longer lasting more vibrant colour, whilst smoothing and making hair healthier and more manageable.\r\n\r\nThe Novafusion Colour Care Conditioner is tone free and can be used with the entire Novafusion Colour Care Shampoo range.\r\n\r\nParaben & Sulfate free.', 'products/de_lorenzo/novafusion/novafusion_conditioner.png', 31.50),
(25, 'De Lorenzo Sand Storm 100g', 'Inspired by the power of this natural phenomenon, Sandstorm dry texture spray provides a matte, dry look with natural texture, medium control and added fullness to the hair. Use on oily roots to eliminate shine.\r\n\r\nContains certified organic Rice and Rooibos Leaf extract.', 'products/de_lorenzo/elements/sand_storm_100g.png', 28.00),
(26, 'De Lorenzo Amplify Volumising Spray 200mL', 'Formulated to provide the volume, control and body of a mousse without the foam, Amplify absorbs into each hair shaft giving hair weightless volume and shine.\r\n\r\nContains certified organic Australian Desert Raisin and organic Tomato.', 'products/de_lorenzo/elements/amplify_volumising_spray_200ml.png', 29.00),
(27, 'De Lorenzo Barrel Wave 150g', 'A curl defining lotion formulated using curl science to create manageable, smooth curls. Delivers long lasting control without the frizz.\r\n\r\nContains certified organic Seaweed.', 'products/de_lorenzo/elements/barrel_wave_150g.png', 29.00),
(28, 'De Lorenzo Motion Foam 250g', 'A rich, non-tacky mousse that delivers a firm, flexible hold to the hair. Provides styling and conditioning properties, great for smoothness and curl retention even in the most humid conditions.\r\n\r\nContains certified organic Rooibos Leaf extract.', 'products/de_lorenzo/elements/motion_foam_250g.png', 30.00),
(29, 'De Lorenzo Granite Hair Lacquer 50g', 'Granite is a strong hold hair lacquer designed to deliver long lasting control, shine and flexibility. Perfect for styling and finishing.\r\n\r\nPerfect size to keep in your handbag, touchup hair kit for dancers or when travelling.\r\n\r\nContains certified organic Cinnamon Bark Oil and Sunflower Seed Cake extract.', 'products/de_lorenzo/elements/granite_hair_lacquer_50g.png', 14.00),
(30, 'De Lorenzo Granite Hair Lacquer 400g', 'Granite is a strong hold hair lacquer designed to deliver long lasting control, shine and flexibility. Perfect for styling and finishing.\r\n\r\nContains certified organic Cinnamon Bark Oil and Sunflower Seed Cake extract.', 'products/de_lorenzo/elements/granite_hair_lacquer_400g.png', 29.00),
(31, 'De Lorenzo Vapour Mist Hair Spray 50g', 'Shape and texturise with this medium hold hairspray that provides perfect control without weighing down the hair. Containing natural UV sun protection, Vapour Mist is non-tacky allowing existing style to be reshaped and used repeatedly.\r\n\r\nContains certified organic Lime Fruit Oil.', 'products/de_lorenzo/elements/vapour_mist_hair_spray_50g.png', 14.00),
(32, 'De Lorenzo Vapour Mist Hair Spray 400g', 'Shape and texturise with this medium hold hairspray that provides perfect control without weighing down the hair. Containing natural UV sun protection, Vapour Mist is non-tacky allowing existing style to be reshaped and used repeatedly.\r\n\r\nContains certified organic Lime Fruit Oil.', 'products/de_lorenzo/elements/vapour_mist_hair_spray_400g.png', 29.00),
(33, 'De Lorenzo Mudslide Shine Paste 100g', 'A strong hold sculpting paste that provides texture, definition and shine.\r\n\r\nContains certified organic Fig and Basil Leaf extract.', 'products/de_lorenzo/elements/mudslide_shine_paste_100g.png', 29.00),
(34, 'Angel Professional Dual Repair Shampoo 500mL', 'Angel Dual Repair Shampoo is formulated with the restorative qualities of Royal Iris combined with the moisturising benefits of Padina Pavonica, targeting dry and damaged strands. This shampoo cleanses as it deeply nourishes, reinforcing hair\'s strength and enhancing its smoothness and hydration for a silky, healthy appearance.\r\n\r\nNO SLS\r\nNO PARABENS\r\nNO COCAMIDE DEA\r\nNO FORMALDEHYDE\r\nNO ENVIRON HORMONES\r\nNO ANIMAL TESTING\r\nNATURAL INGREDIENTS\r\nGMP AND FDA CERTIFICATED', 'products/angel_en_provence/deep_sea/dual_repair_shampoo_500ml.png', 32.90),
(35, 'Angel Professional Dual Repair Conditioner 500mL', 'Angel Dual Repair Conditioner is specially enriched with the nourishing properties of Royal Iris and Padina Pavonica to repair dry and damaged hair. It replenishes and strengthens while cleansing, leaving the hair silky, smooth and hydrated.\r\n\r\nNO SLS\r\nNO PARABENS\r\nNO COCAMIDE DEA\r\nNO FORMALDEHYDE\r\nNO ENVIRON HORMONES\r\nNO ANIMAL TESTING\r\nNATURAL INGREDIENTS\r\nGMP AND FDA CERTIFICATED', 'products/angel_en_provence/deep_sea/dual_repair_conditioner_500ml.png', 32.90),
(36, 'Angel Professional Dual Repair Hair Serum 50mL', 'Utilising advanced dual extraction technology, this regenerative hair serum is fortified with Padina Pavonica, offering potent anti-aging and nourishing effects. Infused with Argan Oil and Camellia Oleifera Seed Oil, it leaves hair silky and lustrous, while balancing the hair’s pH to safeguard against environmental contaminants.\r\n\r\nNO SLS\r\nNO PARABENS\r\nNO COCAMIDE DEA\r\nNO FORMALDEHYDE\r\nNO ENVIRON HORMONES\r\nNO ANIMAL TESTING\r\nNATURAL INGREDIENTS\r\nGMP AND FDA CERTIFICATED', 'products/angel_en_provence/deep_sea/dual_repair_hair_serum_50ml.png', 32.90),
(37, 'Angel Professional Colour Protect Shampoo 500mL', 'This Deep Sea Colour Protecting Shampoo is crafted to gently purify and moisturise hair that\'s been coloured or chemically treated. Fortified with the essence of Wild Sakura and harmoniously enriched with the hydration of Padina Pavonica, it acts to prevent colour washout and mitigate dryness after dyeing and chemical processes. It effectively prolongs the vibrancy and health of your hair\'s colour, ensuring a sustained, brilliant shine.\r\n\r\nNO Parabens\r\nNO Sodium Lauryl Sulfate\r\nNO Cocamide DEA\r\nNO Formaldehyde\r\nNO Environ Hormones\r\nNO Animal Testing\r\nNatural Ingredients\r\nGMP and FDA Certificated', 'products/angel_en_provence/deep_sea/colour_protect_shampoo_500ml.png', 32.90),
(38, 'Angel Professional Colour Protect Conditioner 500mL', 'Angel Color Protect conditioner hydrates and protects colored and chemically treated hair. Enriched with smoothing Wild Sakura and perfectly combined with the deeply nourishing Padina pavonica, to protect, nourish and hydrate colored and damaged hair. Wheat Protein and Argan Oil also help to strengthen the hair fiber, leaving hair smooth and soft.\r\n\r\nNO SLS\r\nNO PARABENS\r\nNO COCAMIDE DEA\r\nNO FORMALDEHYDE\r\nNO ENVIRON HORMONES\r\nNO ANIMAL TESTING\r\nNATURAL INGREDIENTS\r\nGMP AND FDA CERTIFICATED', 'products/angel_en_provence/deep_sea/colour_protect_conditioner_500ml.png', 32.90),
(39, 'Angel Professional Colour Protect Hydration Cream 200mL', 'Angel Professional colour care nourishing cream offers essential protection and restoration for dry, damaged hair. Featuring Wild Sakura extract for unmatched colour retention and Padina Pavonica to revitalise and hydrate, this leave-in treatment safeguards your hair from the sun\'s rays and the thermal impact of styling devices. Keep it in to maintain colour brilliance and impart a lasting, beautiful shine throughout your day.\r\n\r\nNO SLS\r\nNO PARABENS\r\nNO COCAMIDE DEA\r\nNO FORMALDEHYDE\r\nNO ENVIRON HORMONES\r\nNO ANIMAL TESTING\r\nNATURAL INGREDIENTS\r\nGMP AND FDA CERTIFICATED', 'products/angel_en_provence/deep_sea/colour_protect_hydration_cream_200ml.png', 35.90),
(40, 'Angel Professional No Yellow Crystalline Shampoo 500mL', 'Tailored for blonde hair, this Shampoo is expertly crafted to neutralise undesired yellow and brassy hues. It features a unique blend of Shea Butter, Rosa Centifolia Flower, and Orange Flower Oil, which work together to illuminate blonde hair with a radiant, luminous shine while providing a gentle cleanse. The inclusion of Padina Pavonica enriches the hair with its nourishing and smoothing effects, ensuring hair remains silky, gleaming, and voluminous.\r\n\r\nNO SLS\r\nNO PARABENS\r\nNO COCAMIDE DEA\r\nNO FORMALDEHYDE\r\nNO ENVIRON HORMONES\r\nNO ANIMAL TESTING\r\nNATURAL INGREDIENTS\r\nGMP AND FDA CERTIFICATED', 'products/angel_en_provence/deep_sea/no_yellow_crystalline_shampoo_500ml.png', 32.90),
(41, 'Angel Professional No Yellow Crystalline Conditioner 500mL', 'Specifically designed for blonde hair, this Conditioner helps to remove unwanted yellow and brassy tones. Contains a combination of Shea Butter, Rosa Centifolia Flower and Orange Flower Oil to create brilliant, luminous shine in blonde hair, while gently cleansing. Padina Pavonica\'s nourishing and smoothing properties leaves hair silky, shiny and full.\r\n\r\nNO Parabens\r\nNO Sodium Lauryl Sulfate\r\nNO Cocamide DEA\r\nNO Formaldehyde\r\nNO Environ Hormones\r\nNO Animal Testing\r\nNatural Ingredients\r\nGMP and FDA Certificated', 'products/angel_en_provence/deep_sea/no_yellow_crystalline_conditioner_500ml.png', 32.90),
(42, 'Angel Professional No Yellow Crystalline Hair Serum 50mL', 'This enriching Glow Serum blends the nourishing power of Macadamia Oil, Argan Oil, Rosa Centifolia Flower Oil, and Orange Flower Oil to smooth and protect hair from root to tip. Featuring Padina Pavonica to fortify and enhance shine, softness, and silkiness throughout the day. It’s specially formulated with toning properties to diminish unwanted yellow and brassy tones in blonde hair.\r\n\r\nNO SLS\r\nNO PARABENS\r\nNO COCAMIDE DEA\r\nNO FORMALDEHYDE\r\nNO ENVIRON HORMONES\r\nNO ANIMAL TESTING\r\nNATURAL INGREDIENTS\r\nGMP AND FDA CERTIFICATED', 'products/angel_en_provence/deep_sea/no_yellow_crystalline_hair_serum_50ml.png', 32.90),
(43, 'Angel Professional Volumising Cream 250mL', 'Angel Deep Sea Volumising Cream is a thickifying product to add volume and body to your style. Hair is left smooth and shiny.', 'products/angel_en_provence/deep_sea/volumising_cream_250ml.png', 29.90),
(44, 'Angel Professional Shine Crystal Wax 100mL', 'A versatile wax for creating shine, texture, movement and definition for your desired style. Great for all hair types and textures.', 'products/angel_en_provence/deep_sea/shine_crystal_wax_100ml.png', 29.90),
(45, 'Brelil Biotraitement Hair BB Cream 150ml', 'Brelil BB Cream is a leave in spray treatment that gives 10 benefits in one product. Hair is left feeling silky soft and shiny. Suitable for all hair types. \r\n\r\n1. Detangles hair\r\n2. Repairs and nourishes\r\n3. Prevents split ends\r\n4. Anti-frizz\r\n5. Reduces drying time\r\n6. Heat protection\r\n7. Enhances and protects hair colour\r\n8. Provides hair with body and volume\r\n9. Moisturises \r\n10. Improves styling hold', 'products/brelil/bio_beauty/bb_cream_150ml.png', 39.90),
(46, 'Brelil BB Mousse Gourmand 250ml', 'Brelil BB Mousse Gourmand is an intense nourishing and conditioning mousse. It tames and softens hair, while calming frizz and adds shine. Gourmand fragrance.', 'products/brelil/bio_beauty/bb_mousse_250ml.png', 39.90),
(47, 'i.N.O Instant Hair Repair Mask 50mL', 'Repairs hair damage caused by colour, heat, and chemicals. Get stronger, smoother, and shinier hair with the revolutionary i.N.O instant hair repair mask!\r\n\r\nThis clinically-proven, revolutionary leave-in hair repair mask is a simple 1-step system that penetrates the hair follicle to:\r\n\r\n• Reverse damage due to chemicals & colour treatments\r\n• Detangle and control frizz\r\n• Protect hair from damaging heat-styling\r\n• Help keep colour from fading\r\n\r\nGet instant stronger, softer, and smoother hair today with this 4 minute treatment!', 'products/ino_haircare/repair_mask_50ml.png', 69.00);

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `region_id` int(11) NOT NULL,
  `region_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`region_id`, `region_name`) VALUES
(1, 'Northland'),
(2, 'Auckland'),
(3, 'Bay of Plenty'),
(4, 'Waikato'),
(5, 'Gisborne'),
(6, 'Hawke\'s Bay'),
(7, 'Taranaki'),
(8, 'Manawatu-Whanganui'),
(9, 'Wellington'),
(10, 'Marlborough'),
(11, 'Nelson-Tasman'),
(12, 'Canterbury'),
(13, 'West Coast'),
(14, 'Otago'),
(15, 'Southland');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_rates`
--

CREATE TABLE `shipping_rates` (
  `region_id` int(11) NOT NULL,
  `shipping_type` enum('urban','rural') NOT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping_rates`
--

INSERT INTO `shipping_rates` (`region_id`, `shipping_type`, `price`) VALUES
(1, 'urban', 9.00),
(1, 'rural', 15.00),
(2, 'urban', 9.00),
(2, 'rural', 15.00),
(3, 'urban', 9.00),
(3, 'rural', 15.00),
(4, 'urban', 9.00),
(4, 'rural', 15.00),
(5, 'urban', 9.00),
(5, 'rural', 15.00),
(6, 'urban', 9.00),
(6, 'rural', 15.00),
(7, 'urban', 9.00),
(7, 'rural', 15.00),
(8, 'urban', 9.00),
(8, 'rural', 15.00),
(9, 'urban', 9.00),
(9, 'rural', 15.00),
(10, 'urban', 9.00),
(10, 'rural', 15.00),
(11, 'urban', 9.00),
(11, 'rural', 15.00),
(12, 'urban', 9.00),
(12, 'rural', 15.00),
(13, 'urban', 9.00),
(13, 'rural', 15.00),
(14, 'urban', 9.00),
(14, 'rural', 15.00),
(15, 'urban', 9.00),
(15, 'rural', 15.00);

-- --------------------------------------------------------

--
-- Table structure for table `slideshow`
--

CREATE TABLE `slideshow` (
  `id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slideshow`
--

INSERT INTO `slideshow` (`id`, `image_url`) VALUES
(1, 'slideshow/slideshow-1.jpg'),
(2, 'slideshow/slideshow-2.jpg'),
(3, 'slideshow/slideshow-3.jpg'),
(4, 'slideshow/slideshow-4.jpg'),
(5, 'slideshow/slideshow-5.jpg'),
(6, 'slideshow/slideshow-6.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `social_media_links`
--

CREATE TABLE `social_media_links` (
  `id` int(11) NOT NULL,
  `platform` varchar(50) NOT NULL,
  `logo_url` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `social_media_links`
--

INSERT INTO `social_media_links` (`id`, `platform`, `logo_url`, `link`) VALUES
(1, 'Facebook', 'img/socials/facebook-logo.png', 'https://www.facebook.com/hair4ubulls/'),
(2, 'Instagram', 'img/socials/instagram-logo.png', 'https://www.instagram.com/hair4ubulls');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_page`
--
ALTER TABLE `about_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_images`
--
ALTER TABLE `admin_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_logo`
--
ALTER TABLE `business_logo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hair_services`
--
ALTER TABLE `hair_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`region_id`);

--
-- Indexes for table `shipping_rates`
--
ALTER TABLE `shipping_rates`
  ADD PRIMARY KEY (`region_id`,`shipping_type`);

--
-- Indexes for table `slideshow`
--
ALTER TABLE `slideshow`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_media_links`
--
ALTER TABLE `social_media_links`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_page`
--
ALTER TABLE `about_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_images`
--
ALTER TABLE `admin_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `business_logo`
--
ALTER TABLE `business_logo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hair_services`
--
ALTER TABLE `hair_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment_details`
--
ALTER TABLE `payment_details`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `region_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `slideshow`
--
ALTER TABLE `slideshow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `social_media_links`
--
ALTER TABLE `social_media_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_images`
--
ALTER TABLE `admin_images`
  ADD CONSTRAINT `admin_images_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD CONSTRAINT `payment_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `shipping_rates`
--
ALTER TABLE `shipping_rates`
  ADD CONSTRAINT `shipping_rates_ibfk_1` FOREIGN KEY (`region_id`) REFERENCES `regions` (`region_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
