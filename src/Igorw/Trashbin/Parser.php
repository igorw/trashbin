<?php

namespace Igorw\Trashbin;

use Symfony\Component\HttpFoundation\Request;

class Parser
{
    private $languages;

    public function __construct(array $languages)
    {
        $this->languages = $languages;
    }

    public function createPasteFromRequest(Request $request)
    {
        $content = $request->get('content', '');
        $content = $this->normalizeContent($content);

        $id = $this->generateId($content);

        $paste = array(
            'content'   => $content,
        );

        $language = $request->get('language', '');
        if (in_array($language, $this->languages)) {
            $paste['language'] = $language;
        }

        return array($id, $paste);
    }

    public function normalizeContent($content)
    {
        return preg_replace('#\r?\n#', "\n", $content);
    }

    public function generateId($content)
    {
        return substr(hash('sha512', $content . time() . rand(0, 255)), 0, 8);
    }
}
