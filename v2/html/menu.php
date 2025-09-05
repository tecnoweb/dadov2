
<ul id="switchtheme">
    <li class="<?php echo $theme == 'default' ? 'active' : '' ?>">
        <a href="home.php?pagina=<?php echo $page ?>&theme=bootstrap">Default theme</a>
    </li>
    <li class="<?php echo $theme == 'bootstrap' ? 'active' : '' ?>">
        <a href="home.php?page=<?php echo $page ?>&theme=bootstrap">Bootstrap theme</a>
    </li>
    <li class="<?php echo $theme == 'minimal' ? 'active' : '' ?>">
        <a href="home.php?page=<?php echo $page ?>&theme=minimal">Minimal theme</a>
    </li>
</ul>
