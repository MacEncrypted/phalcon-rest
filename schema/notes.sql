--
-- Table structure for table `notes`
--

CREATE TABLE IF NOT EXISTS `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `text` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`title`, `text`) VALUES
('First title', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis maximus mollis faucibus. Etiam eget semper urna, eu finibus urna. Nulla nec erat id sapien scelerisque malesuada. Vivamus risus libero, laoreet eu vestibulum a, mollis vitae lacus. Donec ut viverra tortor. Donec imperdiet, enim in tempus commodo, lacus leo ultricies ligula, in mattis arcu nulla eget eros. Sed feugiat, sapien eu porttitor maximus, arcu urna aliquam dui, ut molestie libero odio in dui. Morbi sit amet felis ut lacus faucibus commodo. '),
('Scnd title', 'Pellentesque sit amet ligula vitae justo dictum pretium. Sed sed gravida enim. Maecenas ut dignissim mi. In vitae felis a urna accumsan accumsan. Integer eros nulla, venenatis ac urna eu, vulputate cursus justo. Pellentesque varius diam leo, non pellentesque est venenatis non. Fusce varius efficitur orci, ut tincidunt quam blandit sit amet. Aliquam eget bibendum velit. Donec euismod accumsan urna, non consectetur ante vehicula a. Fusce eu facilisis ante, eu rutrum eros. Mauris faucibus aliquet justo at sodales. Sed malesuada scelerisque congue. '),
('Third title', 'Vivamus consequat volutpat est, eget condimentum mauris tincidunt id. Curabitur laoreet velit vel sodales finibus. Aliquam varius vehicula dui ac sollicitudin. Cras vel faucibus ex, sit amet fringilla justo. Integer at arcu non est malesuada blandit sed et erat. Nulla scelerisque dictum vestibulum. Aliquam quam urna, mattis nec venenatis nec, vestibulum eu nisl. In ut sodales nunc. ');