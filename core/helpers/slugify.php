<?php

function slugify($text)
{
    // Chuyển sang lowercase
    $text = mb_strtolower($text, 'UTF-8');

    // Thay thế tiếng Việt có dấu thành không dấu
    $text = preg_replace([
        '/[áàảãạăắằẳẵặâấầẩẫậ]/u',
        '/[éèẻẽẹêếềểễệ]/u',
        '/[iíìỉĩị]/u',
        '/[óòỏõọôốồổỗộơớờởỡợ]/u',
        '/[úùủũụưứừửữự]/u',
        '/[ýỳỷỹỵ]/u',
        '/[đ]/u',
    ], [
        'a',
        'e',
        'i',
        'o',
        'u',
        'y',
        'd'
    ], $text);

    // Xóa ký tự không phải chữ, số hoặc khoảng trắng
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);

    // Thay khoảng trắng/thừa bằng dấu gạch ngang
    $text = preg_replace('/[\s-]+/', '-', trim($text));

    return $text;
}
