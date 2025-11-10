<?php

/**
 * Welcome Controller (Trongate v2 Structure)
 *
 * Default landing page logic now resides at the module root.
 */
class Welcome extends Trongate {

    /**
     * Render the public-facing welcome page.
     */
    public function index(): void {
        $data['view_module'] = 'welcome';
        $data['view_file'] = 'welcome';
        $data['additional_includes_top'] = [
            'welcome_module/css/welcome.css'
        ];

        $this->module('localizations');
        $data['t'] = $this->localizations->_translator(
            get_language()
        );

        $this->template('public', $data);
    }

}

