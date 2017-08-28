<?php
namespace AttachmentBundle\Parser;

final class OpenGraphParser
{
    /** @var \DOMNodeList */
    private $metaTags;

    public function parse(string $origURL, \DOMDocument $document)
    {
        $this->metaTags = $document->getElementsByTagName('meta');

        return [
            'basic' => $this->fetchBasic($origURL, $document),
        ];
    }

    private function fetchBasic(string $origURL, \DOMDocument $document): array
    {
        $result = [
            'title' => '',
            'description' => '',
            'url' => $origURL,
            'image' => ''
        ];

        $titleElements = $document->getElementsByTagName('title');
        $descriptionElements = $this->getMetaTags('description', 'name');


        if($titleElements->length) {
            $result['title'] = $titleElements->item(0)->textContent;
        }

        if(count($descriptionElements)) {
            $result['description'] = $descriptionElements[0]->attributes->getNamedItem('content')->textContent;
        }

        $linkElements = $document->getElementsByTagName('link');

        /** @var \DOMNode $linkElement */
        foreach($linkElements as $linkElement) {
            if($linkElement->attributes->getNamedItem('rel') && $linkElement->attributes->getNamedItem('rel')->nodeValue === 'image_src') {
                $attr = $linkElement->attributes->getNamedItem('href');

                if(is_object($attr) && isset($attr->nodeValue)) {
                    $href = trim($attr->nodeValue);

                    if(strlen($href)) {
                        $result['image'] = $href;
                    }
                }
            }
        }

        return $result;
    }

//    private function fetchOG(\DOMDocument $document): array
//    {
//
//        $result = [
//            'basic' => $this->fetchOGBasic(),
//            'images' => $this->fetchOGImages(),
//            'audios' => $this->fetchOGAudios(),
//            'videos' => $this->fetchOGVideos(),
//        ];
//
//        return $result;
//    }

//    private function fetchOGBasic()
//    {
//        $attributes = [
//            'og:title' => '',
//            'og:type' => '',
//            'og:url' => '',
//            'og:description' => '',
//            'og:determiner' => '',
//            'og:locale' => '',
//            'og:locale:alternate' => '',
//            'og:site_name' => '',
//            'og:image' => '',
//            'og:video' => '',
//            'og:audio' => '',
//        ];
//
//        foreach(array_keys($attributes) as $attr) {
//            $elements = $this->getMetaTags($attr);
//
//            $attributes[$attr] = count($elements)
//                ? $elements[0]->attributes->getNamedItem('content')->textContent
//                : '';
//        }
//
//        return $attributes;
//    }

    /*private function fetchOGImages()
    {
        $result = [];
        $attributes = [
            'og:image',
            'og:image:url',
            'og:image:secure_url',
            'og:image:type',
            'og:image:width',
            'og:image:height',
        ];

        foreach($attributes as $attr) {
            $elements = $this->getMetaTags($attr);

            if(count($elements)) {
                for($index = 0; $index < count($elements); $index++) {
                    if(! isset($result[$index])) {
                        $result[$index] = [
                            'og:image' => '',
                            'og:image:url' => '',
                            'og:image:secure_url' => '',
                            'og:image:type' => '',
                            'og:image:width' => '',
                            'og:image:height' => '',
                        ];
                    }

                    $result[$index][$attr] = $elements[0]->attributes->getNamedItem('content')->textContent;
                }
            }
        }

        foreach($result as &$res) {
            if((! strlen($res['og:image'])) && strlen($res['og:image:url'])) $res['og:image'] = $res['og:image:url'];
            if((! strlen($res['og:image:url'])) && strlen($res['og:image'])) $res['og:image:url'] = $res['og:image'];
        }

        return $result;
    }*/

    /*private function fetchOGVideos()
    {
        $result = [];
        $attributes = [
            'og:video',
            'og:video:url',
            'og:video:secure_url',
            'og:video:type',
            'og:video:width',
            'og:video:height',
        ];

        foreach($attributes as $attr) {
            $elements = $this->getMetaTags($attr);

            if(count($elements)) {
                for($index = 0; $index < count($elements); $index++) {
                    if(! isset($result[$index])) {
                        $result[$index] = [
                            'og:video' => '',
                            'og:video:url' => '',
                            'og:video:secure_url' => '',
                            'og:video:type' => '',
                            'og:video:width' => '',
                            'og:video:height' => '',
                        ];
                    }

                    $result[$index][$attr] = $elements[0]->attributes->getNamedItem('content')->textContent;
                }
            }
        }

        foreach($result as &$res) {
            if((! strlen($res['og:video'])) && strlen($res['og:video:url'])) $res['og:video'] = $res['og:video:url'];
            if((! strlen($res['og:video:url'])) && strlen($res['og:video'])) $res['og:video:url'] = $res['og:video'];
        }

        return $result;
    }*/

    /*private function fetchOGAudios()
    {
        $result = [];
        $attributes = [
            'og:audio',
            'og:audio:url',
            'og:audio:secure_url',
            'og:audio:type',
            'og:audio:width',
            'og:audio:height',
        ];

        foreach($attributes as $attr) {
            $elements = $this->getMetaTags($attr);

            if(count($elements)) {
                for($index = 0; $index < count($elements); $index++) {
                    if(! isset($result[$index])) {
                        $result[$index] = [
                            'og:audio' => '',
                            'og:audio:url' => '',
                            'og:audio:secure_url' => '',
                            'og:audio:type' => '',
                            'og:audio:width' => '',
                            'og:audio:height' => '',
                        ];
                    }

                    $result[$index][$attr] = $elements[0]->attributes->getNamedItem('content')->textContent;
                }
            }
        }

        foreach($result as &$res) {
            if((! strlen($res['og:audio'])) && strlen($res['og:audio:url'])) $res['og:audio'] = $res['og:audio:url'];
            if((! strlen($res['og:audio:url'])) && strlen($res['og:audio'])) $res['og:audio:url'] = $res['og:audio'];
        }

        return $result;
    }*/


    private function getMetaTags(string $property, string $attrName = 'property'): array
    {
        /** @var \DOMNode[] $result */
        $result = [];

        for($index = 0; $index < $this->metaTags->length; $index++) {
            $node = $this->metaTags->item($index);

            if($attrNode = $node->attributes->getNamedItem($attrName)) {
                if($attrNode->textContent === $property) {
                    $result[] = $node;
                }
            }
        }

        return $result;
    }


    public function getOG(string $origURL, string $content): array
    {
        libxml_use_internal_errors(true);
        $document = new \DOMDocument($content);
        $document->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        if($document === false) {
            return [];
        }else{
            return $this->parse($origURL, $document);
        }
    }
}