<?php
class Localizations extends Trongate {

    private $default_limit = 20;
    private $per_page_options = array(10, 20, 50, 100);    

        /**
     * Maps a string of locales to their respective languages.
     *
     * @var array
     */
    public const LOCALE_MAPPINGS = [
        'da' => 'da_DK',
        'en' => 'en_US'
    ];

    /**
     * The fallback locale to use when no locale is specified.
     *
     * @var string
     */
    public string $locale = 'da_DK';

    /**
     * INTL NumberFormatter instance for currency formatting.
     *
     * @var NumberFormatter|null
     */
    public ?NumberFormatter $currencyFormatter = null;

    /**
     * The currency code to use for currency formatting.
     *
     * @var string
     */
    public string $currency = 'DKK';

    public function _load_language(string $language): static
    {
        $this->locale = $this->compose_locale($language);
        $this->currencyFormatter = new NumberFormatter($this->locale, NumberFormatter::CURRENCY);
        $this->currency = $currency ?? $this->currencyFormatter->getTextAttribute(NumberFormatter::CURRENCY_CODE);

        return $this;
    }

    private function compose_locale(string $language): ?string
    {
        if (isset(self::LOCALE_MAPPINGS[$language])) {
            return self::LOCALE_MAPPINGS[$language];
        }

        $locale = Locale::composeLocale([
            'language' => Locale::getPrimaryLanguage($language),
            'script' => Locale::getScript($language),
            'region' => Locale::getRegion($language),
        ]);

        return $locale ?: null;
    }

    public function _get_language_from_header(): ?string
    {
        $locale = Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);

        return $locale ?: null;
    }
    
    /**
     * Display a webpage with a form for creating or updating a record.
     */
    public function create(): void {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        $update_id = (int) segment(3);
        $submit = post('submit');

        if (($submit === '') && ($update_id>0)) {
            $data = $this->get_data_from_db($update_id);
        } else {
            $data = $this->get_data_from_post();
        }

        if ($update_id>0) {
            $data['headline'] = 'Update Localization Record';
            $data['cancel_url'] = BASE_URL.'localizations/show/'.$update_id;
        } else {
            $data['headline'] = 'Create New Localization Record';
            $data['cancel_url'] = BASE_URL.'localizations/manage';
        }

        $data['form_location'] = BASE_URL.'localizations/submit/'.$update_id;
        $data['view_file'] = 'create';
        $this->template('admin', $data);
    }

    /**
     * Display a webpage to manage records.
     *
     * @return void
     */
    public function manage(): void {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        if (segment(4) !== '') {
            $data['headline'] = 'Search Results';
            $searchphrase = trim($_GET['searchphrase']);
            $params['module'] = '%'.$searchphrase.'%';
            $params['locale'] = '%'.$searchphrase.'%';
            $params['key'] = '%'.$searchphrase.'%';
            $params['value'] = '%'.$searchphrase.'%';
            $sql = 'select * from localizations
            WHERE module LIKE :module
            OR locale LIKE :locale
            OR key LIKE :key
            OR value LIKE :value
            ORDER BY created desc';
            $all_rows = $this->model->query_bind($sql, $params, 'object');
        } else {
            $data['headline'] = 'Manage Localizations';
            $all_rows = $this->model->get('created desc');
        }

        $pagination_data['total_rows'] = count($all_rows);
        $pagination_data['page_num_segment'] = 3;
        $pagination_data['limit'] = $this->get_limit();
        $pagination_data['pagination_root'] = 'localizations/manage';
        $pagination_data['record_name_plural'] = 'localizations';
        $pagination_data['include_showing_statement'] = true;
        $data['pagination_data'] = $pagination_data;

        $data['rows'] = $this->reduce_rows($all_rows);
        $data['selected_per_page'] = $this->get_selected_per_page();
        $data['per_page_options'] = $this->per_page_options;
        $data['view_module'] = 'localizations';
        $data['view_file'] = 'manage';
        $this->template('admin', $data);
    }

    /**
     * Display a webpage showing information for an individual record.
     *
     * @return void
     */
    public function show(): void {
        $this->module('trongate_security');
        $token = $this->trongate_security->_make_sure_allowed();
        $update_id = (int) segment(3);

        if ($update_id === 0) {
            redirect('localizations/manage');
        }

        $data = $this->get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data === false) {
            redirect('localizations/manage');
        } else {
            $data['update_id'] = $update_id;
            $data['headline'] = 'Localization Information';
            $data['view_file'] = 'show';
            $this->template('admin', $data);
        }
    }

    /**
     * Handle submitted record data.
     *
     * @return void
     */
    public function submit(): void {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        $submit = post('submit', true);

        if ($submit === 'Submit') {

            $this->validation->set_rules('module', 'Module', 'required|min_length[2]|max_length[255]');
            $this->validation->set_rules('locale', 'Locale', 'required|min_length[2]|max_length[255]');
            $this->validation->set_rules('key', 'Key', 'required|min_length[2]|max_length[255]');
            $this->validation->set_rules('value', 'Value', 'required|min_length[2]|max_length[255]');
            $this->validation->set_rules('created', 'Created', 'required|valid_datetimepicker_us');
            $this->validation->set_rules('updated', 'Updated', 'required|valid_datetimepicker_us');

            $result = $this->validation->run();

            if ($result === true) {

                $update_id = (int) segment(3);
                $data = $this->get_data_from_post();
                $data['updated'] = str_replace(' at ', '', $data['updated']);
                $data['updated'] = date('Y-m-d H:i', strtotime($data['updated']));
                $data['created'] = str_replace(' at ', '', $data['created']);
                $data['created'] = date('Y-m-d H:i', strtotime($data['created']));
                
                if ($update_id>0) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'localizations');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'localizations');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('localizations/show/'.$update_id);

            } else {
                //form submission error
                $this->create();
            }

        }

    }

    /**
     * Handle submitted request for deletion.
     *
     * @return void
     */
    public function submit_delete(): void {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        $submit = post('submit');
        $params['update_id'] = (int) segment(3);

        if (($submit === 'Yes - Delete Now') && ($params['update_id']>0)) {
            //delete all of the comments associated with this record
            $sql = 'delete from trongate_comments where target_table = :module and update_id = :update_id';
            $params['module'] = 'localizations';
            $this->model->query_bind($sql, $params);

            //delete the record
            $this->model->delete($params['update_id'], 'localizations');

            //set the flashdata
            $flash_msg = 'The record was successfully deleted';
            set_flashdata($flash_msg);

            //redirect to the manage page
            redirect('localizations/manage');
        }
    }

    /**
     * Set the number of items per page.
     *
     * @param int $selected_index Selected index for items per page.
     *
     * @return void
     */
    public function set_per_page(int $selected_index): void {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        if (!is_numeric($selected_index)) {
            $selected_index = $this->per_page_options[1];
        }

        $_SESSION['selected_per_page'] = $selected_index;
        redirect('localizations/manage');
    }

    /**
     * Get the selected number of items per page.
     *
     * @return int Selected items per page.
     */
    private function get_selected_per_page(): int {
        $selected_per_page = (isset($_SESSION['selected_per_page'])) ? $_SESSION['selected_per_page'] : 1;
        return $selected_per_page;
    }

    /**
     * Reduce fetched table rows based on offset and limit.
     *
     * @param array $all_rows All rows to be reduced.
     *
     * @return array Reduced rows.
     */
    private function reduce_rows(array $all_rows): array {
        $rows = [];
        $start_index = $this->get_offset();
        $limit = $this->get_limit();
        $end_index = $start_index + $limit;

        $count = -1;
        foreach ($all_rows as $row) {
            $count++;
            if (($count>=$start_index) && ($count<$end_index)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    /**
     * Get the limit for pagination.
     *
     * @return int Limit for pagination.
     */
    private function get_limit(): int {
        if (isset($_SESSION['selected_per_page'])) {
            $limit = $this->per_page_options[$_SESSION['selected_per_page']];
        } else {
            $limit = $this->default_limit;
        }

        return $limit;
    }

    /**
     * Get the offset for pagination.
     *
     * @return int Offset for pagination.
     */
    private function get_offset(): int {
        $page_num = (int) segment(3);

        if ($page_num>1) {
            $offset = ($page_num-1)*$this->get_limit();
        } else {
            $offset = 0;
        }

        return $offset;
    }

    /**
     * Get data from the database for a specific update_id.
     *
     * @param int $update_id The ID of the record to retrieve.
     *
     * @return array|null An array of data or null if the record doesn't exist.
     */
    private function get_data_from_db(int $update_id): ?array {
        $record_obj = $this->model->get_where($update_id, 'localizations');

        if ($record_obj === false) {
            $this->template('error_404');
            die();
        } else {
            $data = (array) $record_obj;
            return $data;        
        }
    }

    /**
     * Get data from the POST request.
     *
     * @return array Data from the POST request.
     */
    private function get_data_from_post(): array {
        $data['module'] = post('module', true);
        $data['locale'] = post('locale', true);
        $data['key'] = post('key', true);
        $data['value'] = post('value', true);
        $data['created'] = post('created', true);
        $data['updated'] = post('updated', true);        
        return $data;
    }

}