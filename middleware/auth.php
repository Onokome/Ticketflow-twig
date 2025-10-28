<?php
// middleware/auth.php

function checkAuth() {
    session_start();
    
    if (!isset($_SESSION['user'])) {
        // Check localStorage simulation
        if (isset($_COOKIE['ticketapp_session'])) {
            $sessionData = json_decode($_COOKIE['ticketapp_session'], true);
            if ($sessionData && isset($sessionData['user']) && isset($sessionData['expires'])) {
                if (time() < $sessionData['expires']) {
                    $_SESSION['user'] = $sessionData['user'];
                    return $_SESSION['user'];
                }
            }
        }
        
        http_response_code(401);
        header('Location: /auth/login');
        exit();
    }
    
    return $_SESSION['user'];
}

function optionalAuth() {
    session_start();
    
    if (isset($_SESSION['user'])) {
        return $_SESSION['user'];
    }
    
    // Check localStorage simulation
    if (isset($_COOKIE['ticketapp_session'])) {
        $sessionData = json_decode($_COOKIE['ticketapp_session'], true);
        if ($sessionData && isset($sessionData['user']) && isset($sessionData['expires'])) {
            if (time() < $sessionData['expires']) {
                $_SESSION['user'] = $sessionData['user'];
                return $_SESSION['user'];
            }
        }
    }
    
    return null;
}

function setAuthCookie($user) {
    $sessionData = [
        'user' => $user,
        'expires' => time() + (24 * 60 * 60) // 24 hours
    ];
    setcookie('ticketapp_session', json_encode($sessionData), time() + (24 * 60 * 60), '/');
}

function clearAuthCookie() {
    setcookie('ticketapp_session', '', time() - 3600, '/');
}