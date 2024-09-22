<?php

class Blog extends Trongate {
	function index(): void {
		$data['posts'] = $this->_discover_articles();
        $data['view_module'] = 'blog';
        $data['view_file'] = 'blog';
        $data['additional_includes_top'] = [
            'blog_module/css/blog.css'
        ];
        $this->module('localizations');
        $data['t'] = $this->localizations->_translator(
           get_language()
        );

        $this->template('public', $data);
	}

    private function _discover_articles(): array
    {
        $paths = glob(APPPATH . 'modules/blog/views/articles/*.php');

        $articles = [];

        foreach ($paths as $path) {
            $module_name = substr($path, strrpos($path, '/') + 1);

            $nice_name = str_replace('_', ' ', $module_name);
            $nice_name = mb_convert_case($nice_name, MB_CASE_TITLE, 'UTF-8');

            $articles[$module_name] = $nice_name;
        }

        return array_reverse($articles);
    }
}