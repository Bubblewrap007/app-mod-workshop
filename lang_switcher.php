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
<div style="text-align:right; padding:6px 12px; background:#f0f0f0; font-size:13px; border-bottom:1px solid #ddd;">
    <?php echo implode(' &nbsp;|&nbsp; ', $lang_links); ?>
</div>
