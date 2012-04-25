<?php
/**
 * This file is part of Ezac.
 *
 * Ezac is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Ezac is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Ezac. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Julien Fontanet <julien.fontanet@isonoe.net>
 * @license http://www.gnu.org/licenses/gpl-3.0-standalone.html GPLv3
 *
 * @package Ezac
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
		  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr-FR">
  <head>
	<meta http-equiv="Content-Language" content="fr-FR" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" media="screen" href="ezac.css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript" src="ezac.js"></script>
	<title><?php echo htmlspecialchars($title); ?></title>
  </head>
  <body>
	<div id="page">
	  <h1 id="header">
		<?php echo htmlspecialchars($title); ?>
	  </h1>
	  <ul id="menu">
		<?php foreach ($menu as $text => $url): ?>
		<li>
		  <a href="<?php echo htmlspecialchars($url); ?>">
			<?php echo htmlspecialchars($text); ?>
		  </a>
		</li>
		<?php endforeach; ?>
	  </ul>
	  <div id="content">
		<?php echo $content; ?>
	  </div>
	  <div id="footer">
		<p>
		  <a href="https://github.com/julien-f/ezac"><em>Ezac</em></a> available
		  under
		  the <a href="http://www.gnu.org/licenses/quick-guide-gplv3.html">GPL
		  license</a>.
		</p>
	  </div>
	</div>
  </body>
</html>
