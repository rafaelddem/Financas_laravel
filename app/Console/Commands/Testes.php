<?php

namespace App\Console\Commands;

use App\Models\Carteira;
use App\Models\Comment;
use App\Models\Post;
use App\Tasks\Financial\Money;
use Illuminate\Console\Command;

class Testes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $money = new Money();
        $valor = $money->formatValue(0);
        echo $valor;
        // $originalValue = "R$ 1.8123,52";
        // $valorFinal = preg_replace('/[^0-9]/', '', $originalValue);
        // dd($valorFinal);


        // $post = new Post();
        // $post->text = "Texto";
        // $post->save();

        // $comment1 = new Comment();
        // $comment1->post = 1;
        // $comment1->comment = "comentario";
        // $comment1->save();

        // $comment2 = new Comment();
        // $comment2->post = 1;
        // $comment2->comment = "comentario 2";
        // $comment2->save();

        // $comment3 = new Comment();
        // $comment3->post = 1;
        // $comment3->comment = "comentario 3";
        // $comment3->save();

        // $post = Post::find(1);
        // $comments = $post->comments;
        // foreach ($comments as $comment) {
        //     echo $comment->comment;
        // }

        // $comment = Comment::find(1);
        // echo $comment->post()->getResults()->text;

        // $comment = Carteira::find(1);
        // echo $comment->pessoa()->getResults()->nome;
    }
}
