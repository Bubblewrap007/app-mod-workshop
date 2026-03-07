<?php
$current_lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
$lang_links = [];
foreach ($languages as $code => $name) {
    if ($code === $current_lang) {
        $lang_links[] = '<strong>' . $name . '</strong>';
    } else {
        $lang_links[] = '<a href="set_lang.php?lang=' . $code . '">' . $name . '</a>';
    }
}
?>
<div class="lang-bar">
    <?php echo implode(' &nbsp;|&nbsp; ', $lang_links); ?>
</div>
