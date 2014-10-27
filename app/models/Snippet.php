<?php

use \Michelf\Markdown;

class Snippet extends Eloquent {
  protected $fillable = ['title', 'description', 'body', 'user_id'];

  public static $rules = [
    'title' => 'required|min:4',
    'body' => 'required',
    'description' => 'required'
    ];

  public function user()
  {
    return $this->belongsTo('User');
  }

  public function comments()
  {
    return $this->hasMany('Comment');
  }

  public function votes()
  {
    return $this->hasMany('Vote');
  }

  public function getScore()
  {
    return $this->votes()->where('snippet_id', '=', $this->id)->sum('score');
  }

  public function getMarkdownBody($body)
  {
    $body = Markdown::defaultTransform($body);
    $body = strip_tags($body, '<em><strong><code><blockquote><p><br><kbd>');
    return $body;
  }

  public function hasUserVoted($user_id)
  {
    $vote = $this->votes()->where('user_id', '=', $user_id)->first();
    if ( $vote )
    {
      return (int)$vote->score;
    }
  }

}
