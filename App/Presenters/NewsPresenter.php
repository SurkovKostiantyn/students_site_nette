<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\AbortException;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use stdClass;
use JetBrains\PhpStorm\NoReturn;

/**
 * @middleware MyMiddleware
 */
final class NewsPresenter extends Nette\Application\UI\Presenter
{
    private string $title = 'News';

    public function __construct(
        private readonly Nette\Database\Explorer $database,
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function renderAll(): void // this function is called when the page is loaded
    {
        $this->setView('news');
        $this->getTemplate()->title = $this->title; // set the title

        $this->getTemplate()->news =
            $this->database
            ->table('news')
            ->order('news_id ASC');
    }

    /**
     * @throws BadRequestException
     */
    public function renderShow(int $postId): void
    {
        $post = $this->database
            ->table('news')
            ->get($postId);
        if (!$post) {
            $this->error('Post not found');
        }

        $this->getTemplate()->title = $post->news_title;
        $this->getTemplate()->post = $post;

        // get comments
        $comments = $this->getTemplate()->comments =
            $this->database
            ->table('news_comments')
            ->where('news_id', $postId)
            ->order('created_at ASC');
        $this->getTemplate()->comments = $comments;
    }

    public function renderAdd(): void
    {
        $this->setView('add');
        $this->getTemplate()->title = $this->title;
    }

    protected function createComponentAddCommentForm() : Form
    {
        $form = new Form;
        $form->addTextArea('comment', 'Коментар:');
        $form->addSubmit('send', 'Додати');
        $form->onSuccess[] = [$this, 'addCommentFormSucceeded'];

        return $form;
    }

    /**
     * @throws AbortException
     */
    #[NoReturn] public function addCommentFormSucceeded(Form $form, stdClass $data): void
    {
        $postId = $this->getParameter('postId'); // news_id
        $user_id = $this->getUser()->getIdentity()->getId(); // user_id

        $this->database->
        table('news_comments')->
        insert([
            'comment_text' => $data->comment,
            'user_id' => $user_id,
            'news_id' => $postId,
        ]);
        $this->redirect('News:show', $postId);
    }

    protected function createComponentAddNewsForm(): Form
    {
        $form = new Form;
        $form->addText('title');
        $form->addText('short_title');
        $form->addTextArea('text', 'Текст:');
        $form->addUpload('file', 'Зображення:');
        $form->addSubmit('send', 'Додати');
        $form->onSuccess[] = [$this, 'addNewsFormSucceeded'];

        return $form;
    }

    /**
     * @param Form $form
     * @param stdClass $data
     * @return void
     * @throws AbortException
     */
    #[NoReturn] public function addNewsFormSucceeded(Form $form, stdClass $data): void
    {
        $news_user_id = $this->getUser()->getIdentity()->getId();
        $news_user_login = $this->getUser()->getIdentity()->getData()['login'];


        $postId = $this->getParameter('postId');

        if ($postId) {
            $post = $this->database
                ->table('news')
                ->get($postId);
                $data = (array) $data;
            $post->update(
                [
                    'news_title' => $data['title'],
                    'news_short_title' => $data['short_title'],
                    'news_content' => $data['text'],
                    'news_user_id' => $news_user_id,
                    'news_user_login' => $news_user_login,
                ]
            );
            $this->flashMessage('Новину було відредаговано', 'success');
        } else {
            $file = $data->file;
            $file->move('./uploads/news/' . $file->name);
            $image_url = '/uploads/news/'.$file->name;

            $this->database->
            table('news')->
            insert([
                'news_title' => $data->title,
                'news_short_title' => $data->short_title,
                'news_content' => $data->text,
                'image_url' => $image_url,
                'news_user_id' => $news_user_id,
                'news_user_login' => $news_user_login,
            ]);
            $postId = $this->database->table('news')->max('news_id');
        }
        $this->redirect('News:show', $postId);
    }

    /**
     * @throws AbortException
     */
    public function renderEdit(int $postId): void
    {
        $news = $this->database
            ->table('news')
            ->get($postId);

        if (!$news) {
            $this->flashMessage('Пост не знайдено');
        }

        $user_id = $this->getUser()->getIdentity()->getId();
        $news_user_id = $news->news_user_id;

        if ($user_id !== $news_user_id) {
            $this->flashMessage('Ви не можете редагувати чужі пости', 'info');
            $this->redirect('News:show', $postId);
        } else {
            $this->getTemplate()->title = $news->news_title;
            $this->getTemplate()->news = $news;
        }
    }

    /**
     * @throws AbortException
     */
    protected function startup(): void
    {
        parent::startup();

        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
    }
}
