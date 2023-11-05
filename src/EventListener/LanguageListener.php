<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class LanguageListener
{
    public function __construct(public string $lang)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->cookies->has('kbin_lang')) {
            $request->setLocale($request->cookies->get('kbin_lang'));

            return;
        }

        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $request->setLocale($this->lang);

            return;
        }

        // 自动侦测浏览器语言
        preg_match('/^([a-z\d\-]+)/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches);
        $lang = $matches[1];
        $tmp = explode('-', $lang);
        if (count($tmp) > 1) { // 语种+地区的支持
            $tmplangSet = $tmp[0];
            if ($tmplangSet != 'zh') { // 排除中文
                $lang = $tmplangSet;
            }
        }

        $request->setLocale($lang);
        $request->setDefaultLocale($lang);
    }
}
