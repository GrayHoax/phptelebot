<?php
/**
 * MediaGroupHandler.php
 *
 * Helper for sending media groups (albums)
 *
 * @author PHPTelebot Contributors
 * @link https://github.com/radyakaze/phptelebot
 * @license GPL-3.0
 */

namespace PHPTelebot\Helpers;

use PHPTelebot\Exceptions\ValidationException;

/**
 * MediaGroup Handler - Simplifies sending media groups (albums)
 *
 * @see https://core.telegram.org/bots/api#sendmediagroup
 */
class MediaGroupHandler
{
    /**
     * Media items in the group
     *
     * @var array
     */
    private $media = [];

    /**
     * MediaGroupHandler constructor
     */
    public function __construct()
    {
        $this->media = [];
    }

    /**
     * Create new media group
     *
     * @return self
     */
    public static function create()
    {
        return new self();
    }

    /**
     * Add photo to media group
     *
     * @param string $media File path, file_id, or URL
     * @param array $options Additional options (caption, parse_mode, caption_entities, etc.)
     * @return self
     */
    public function addPhoto($media, array $options = [])
    {
        $item = array_merge([
            'type' => 'photo',
            'media' => $media
        ], $options);

        $this->media[] = $item;

        return $this;
    }

    /**
     * Add video to media group
     *
     * @param string $media File path, file_id, or URL
     * @param array $options Additional options (caption, parse_mode, width, height, duration, etc.)
     * @return self
     */
    public function addVideo($media, array $options = [])
    {
        $item = array_merge([
            'type' => 'video',
            'media' => $media
        ], $options);

        $this->media[] = $item;

        return $this;
    }

    /**
     * Add document to media group
     *
     * @param string $media File path, file_id, or URL
     * @param array $options Additional options (caption, parse_mode, etc.)
     * @return self
     */
    public function addDocument($media, array $options = [])
    {
        $item = array_merge([
            'type' => 'document',
            'media' => $media
        ], $options);

        $this->media[] = $item;

        return $this;
    }

    /**
     * Add audio to media group
     *
     * @param string $media File path, file_id, or URL
     * @param array $options Additional options (caption, parse_mode, duration, performer, title, etc.)
     * @return self
     */
    public function addAudio($media, array $options = [])
    {
        $item = array_merge([
            'type' => 'audio',
            'media' => $media
        ], $options);

        $this->media[] = $item;

        return $this;
    }

    /**
     * Get media array for sendMediaGroup
     *
     * @return array
     * @throws ValidationException
     */
    public function build()
    {
        if (empty($this->media)) {
            throw new ValidationException(
                'media',
                'at least one media item',
                'empty array',
                'Media group must contain at least one media item'
            );
        }

        if (count($this->media) > 10) {
            throw new ValidationException(
                'media',
                'maximum 10 media items',
                count($this->media) . ' items',
                'Media group can contain maximum 10 items'
            );
        }

        return $this->media;
    }

    /**
     * Send media group
     *
     * @param int|string $chatId Target chat ID
     * @param array $options Additional options (message_thread_id, disable_notification, etc.)
     * @return string API response
     * @throws ValidationException
     */
    public function send($chatId, array $options = [])
    {
        $data = array_merge([
            'chat_id' => $chatId,
            'media' => json_encode($this->build())
        ], $options);

        return \Bot::send('sendMediaGroup', $data);
    }

    /**
     * Get media count
     *
     * @return int
     */
    public function count()
    {
        return count($this->media);
    }

    /**
     * Clear all media
     *
     * @return self
     */
    public function clear()
    {
        $this->media = [];
        return $this;
    }

    /**
     * Create media group from files array
     *
     * @param array $files Array of file paths
     * @param string $type Media type (photo, video, document, audio)
     * @param array $commonOptions Options to apply to all items
     * @return self
     */
    public static function fromFiles(array $files, $type = 'photo', array $commonOptions = [])
    {
        $handler = new self();

        foreach ($files as $file) {
            $options = $commonOptions;

            if (is_array($file)) {
                $options = array_merge($commonOptions, $file['options'] ?? []);
                $file = $file['path'] ?? $file['file'] ?? $file[0];
            }

            switch ($type) {
                case 'photo':
                    $handler->addPhoto($file, $options);
                    break;
                case 'video':
                    $handler->addVideo($file, $options);
                    break;
                case 'document':
                    $handler->addDocument($file, $options);
                    break;
                case 'audio':
                    $handler->addAudio($file, $options);
                    break;
                default:
                    throw new ValidationException('type', 'photo|video|document|audio', $type);
            }
        }

        return $handler;
    }
}
