<?php
/**
 * MediaGroup Handler Example
 *
 * This example demonstrates how to send media groups (albums) using MediaGroupHandler
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PHPTelebot\Helpers\MediaGroupHandler;

$bot = new PHPTelebot('YOUR_BOT_TOKEN');

// Example 1: Send photo album
$bot->cmd('/album', function () {
    $message = Bot::message();

    try {
        $mediaGroup = MediaGroupHandler::create()
            ->addPhoto('https://picsum.photos/800/600?random=1', ['caption' => 'Photo 1'])
            ->addPhoto('https://picsum.photos/800/600?random=2', ['caption' => 'Photo 2'])
            ->addPhoto('https://picsum.photos/800/600?random=3', ['caption' => 'Photo 3']);

        $mediaGroup->send($message['chat']['id']);

        return Bot::sendMessage('Album sent!');
    } catch (Exception $e) {
        return Bot::sendMessage('Error: ' . $e->getMessage());
    }
});

// Example 2: Send from files
$bot->cmd('/files', function () {
    $message = Bot::message();

    try {
        // Example with local files
        $files = [
            '/path/to/photo1.jpg',
            '/path/to/photo2.jpg',
            '/path/to/photo3.jpg',
        ];

        $mediaGroup = MediaGroupHandler::fromFiles($files, 'photo', [
            'parse_mode' => 'HTML'
        ]);

        $mediaGroup->send($message['chat']['id']);

        return Bot::sendMessage('Photos sent from files!');
    } catch (Exception $e) {
        return Bot::sendMessage('Error: ' . $e->getMessage());
    }
});

// Example 3: Mixed media types
$bot->cmd('/mixed', function () {
    $message = Bot::message();

    try {
        $mediaGroup = MediaGroupHandler::create()
            ->addPhoto('https://picsum.photos/800/600', [
                'caption' => 'A photo'
            ])
            ->addVideo('https://example.com/video.mp4', [
                'caption' => 'A video',
                'width' => 1280,
                'height' => 720
            ]);

        $mediaGroup->send($message['chat']['id']);

        return Bot::sendMessage('Mixed media sent!');
    } catch (Exception $e) {
        return Bot::sendMessage('Error: ' . $e->getMessage());
    }
});

// Example 4: Documents album
$bot->cmd('/docs', function () {
    $message = Bot::message();

    try {
        $mediaGroup = MediaGroupHandler::create()
            ->addDocument('https://example.com/doc1.pdf', [
                'caption' => 'Document 1'
            ])
            ->addDocument('https://example.com/doc2.pdf', [
                'caption' => 'Document 2'
            ]);

        $mediaGroup->send($message['chat']['id']);

        return Bot::sendMessage('Documents sent!');
    } catch (Exception $e) {
        return Bot::sendMessage('Error: ' . $e->getMessage());
    }
});

// Example 5: Build and inspect
$bot->cmd('/inspect', function () {
    $mediaGroup = MediaGroupHandler::create()
        ->addPhoto('https://picsum.photos/800/600?random=1')
        ->addPhoto('https://picsum.photos/800/600?random=2')
        ->addPhoto('https://picsum.photos/800/600?random=3');

    $count = $mediaGroup->count();
    $media = $mediaGroup->build();

    return Bot::sendMessage(
        "Media group has {$count} items.\n\n" .
        "Media array:\n" .
        json_encode($media, JSON_PRETTY_PRINT)
    );
});

// Example 6: With captions and HTML formatting
$bot->cmd('/formatted', function () {
    $message = Bot::message();

    try {
        $mediaGroup = MediaGroupHandler::create()
            ->addPhoto('https://picsum.photos/800/600?random=1', [
                'caption' => '<b>Bold Caption</b>',
                'parse_mode' => 'HTML'
            ])
            ->addPhoto('https://picsum.photos/800/600?random=2', [
                'caption' => '<i>Italic Caption</i>',
                'parse_mode' => 'HTML'
            ])
            ->addPhoto('https://picsum.photos/800/600?random=3', [
                'caption' => '<code>Code Caption</code>',
                'parse_mode' => 'HTML'
            ]);

        $mediaGroup->send($message['chat']['id']);

        return Bot::sendMessage('Formatted album sent!');
    } catch (Exception $e) {
        return Bot::sendMessage('Error: ' . $e->getMessage());
    }
});

// Example 7: Clear and reuse
$bot->cmd('/reuse', function () {
    $message = Bot::message();

    $mediaGroup = MediaGroupHandler::create();

    // First album
    $mediaGroup
        ->addPhoto('https://picsum.photos/800/600?random=1')
        ->addPhoto('https://picsum.photos/800/600?random=2');

    $mediaGroup->send($message['chat']['id']);

    // Clear and create new album
    $mediaGroup->clear()
        ->addPhoto('https://picsum.photos/800/600?random=3')
        ->addPhoto('https://picsum.photos/800/600?random=4');

    $mediaGroup->send($message['chat']['id']);

    return Bot::sendMessage('Two albums sent!');
});

$bot->run();
