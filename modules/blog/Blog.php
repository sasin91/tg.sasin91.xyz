<?php

/**
 * Blog Controller (Trongate v2 Structure)
 *
 * Controller now sits at the module root following the modern Trongate pattern.
 */
class Blog extends Trongate {

    public function index(): void {
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

    public function trongate(): void {
        $data['view_module'] = 'blog';
        $data['view_file'] = 'articles/trongate';
        $data['additional_includes_top'] = [
            'blog_module/css/blog.css',
            'blog_module/css/trongate.css'
        ];
        $data['additional_includes_btm'] = [
            'blog_module/js/trongate.js',
            'blog_module/js/trongate_stars.js'
        ];
        $this->module('localizations');
        $data['t'] = $this->localizations->_translator(
            get_language()
        );

        $this->template('public', $data);
    }

}

