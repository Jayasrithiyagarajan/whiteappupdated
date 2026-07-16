<?php
function abs_path($rel) {
    $p = realpath($rel);
    return $p ? str_replace('\\', '/', $p) : '';
}
