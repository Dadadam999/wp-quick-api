<?php

namespace quickapi\Controller\MetaBox\Integration;

use quickapi\DataBase;
use WpToolKit\Entity\Post;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Controller\ViewLoader;
use WpToolKit\Interface\MetaBoxInterface;
use WpToolKit\Controller\MetaBoxController;

class SettingsQuickForm extends MetaBoxController implements MetaBoxInterface
{
    private string $nonce = 'qapi_integration_meta_nonce';

    public function __construct(
        public ViewLoader $views,
        public Post $parentPost,
        private MetaPoly $secret,
        private MetaPoly $projectId
    ) {
        parent::__construct(
            'settings_quick_form',
            'Quick Form интеграции',
            $parentPost->name
        );
    }

    public function render($post): void
    {
        $this->projectId->value = get_post_meta($post->ID, $this->projectId->name, true);
        $projects = DataBase::getProjects();
        $this->secret->value = get_option($this->secret->name);

        $view = $this->views->getView('settings_quick_form');
        $view->addVariable('currentProjectId', $this->projectId);
        $view->addVariable('secret', $this->secret->value);
        $view->addVariable('projects', $projects);
        $view->addVariable('nonce', $this->nonce);
        $view->addVariable('post', $post);
        $this->views->load($view->name);
    }

    public function callback($postId): void
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $postId)) {
            return;
        }

        if (!isset($_POST[$this->nonce]) || !wp_verify_nonce($_POST[$this->nonce], $this->nonce)) {
            return;
        }

        if (empty($_POST[$this->projectId->name])) {
            return;
        }

        update_post_meta($postId, $this->projectId->name, $_POST[$this->projectId->name]);
    }
}
