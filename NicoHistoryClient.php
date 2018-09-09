<?php

use Goutte\Client;

/**
 * Class NicoHistoryClient
 *
 * ニコニコ動画にログインして視聴履歴を取得する
 */
class NicoHistoryClient
{
    private $client;
    private $history;

    /**
     * NicoHistoryClient constructor.
     *
     * @param $mail_tel string ログインメールアドレスまたは電話番号
     * @param $password string ログインパスワード
     */
    public function __construct($mail_tel, $password)
    {
        $this->client = new Client();

        //login
        $crawler = $this->client->request('GET', 'http://www.nicovideo.jp/login');
        $form = $crawler->filter('form#login_form')->form();
        $crawler = $this->client->submit($form, compact("mail_tel", "password"));
        $crawler->filter('p.notice__text')->each(function ($node) {
            print $node->text() . "\n";
        });
    }

    /**
     * 視聴履歴を取得する
     *
     * @return array 視聴履歴：URL、タイトル、視聴日時、視聴回数
     */
    public function getHistory()
    {
        $this->history = [];
        $crawler = $this->client->request('GET', 'http://www.nicovideo.jp/my/history');
        $crawler->filter('#historyList div.section')->each(function ($node) {
            $title = $node->filter('h5 a')->text();
            $url =  $node->getBaseHref() . $node->filter('h5 a')->attr('href');

            //視聴日時と回数
            list($date, $time, $count) = preg_split('/ /', $node->filter('p.posttime')->text());
            $date = substr(preg_replace('/[年月]/u', '-', $date), 0, 10);
            $dt = new DateTime($date . " " . $time);
            $view_time = $dt->format("Y-m-d h:i:s");
            preg_match("/回数(\d+)回/", $count, $matches);
            $view_count =  isset($matches[1]) ? (int)$matches[1] : 1;

            $this->history[] = compact("url", "title", "view_time", "view_count");
        });
        return $this->history;
    }
}
