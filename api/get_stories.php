<?php
$file = '../data/stories.json';

if(file_exists($file)){
    echo file_get_contents($file);
} else {
    echo json_encode([]);
}
?>