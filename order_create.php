<?php
    // (C)
    // ログインフィルターの読み込み
    require_once 'login_filter.php';
    require_once 'models/Order.php';
    session_start();
    $errors = $_SESSION['errors'];
    $_SESSION['errors'] = null;
    $order = $_SESSION['order'];
    // $_SESSION['order'] = null;
    
    include_once 'views/order_create_view.php';