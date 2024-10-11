<?php
class Live_streams extends Trongate {

    private $default_limit = 20;
    private $per_page_options = array(10, 20, 50, 100);    

    public function _init_filezone_settings() {
        $data['targetModule'] = 'live_streams';
        $data['destination'] = 'live_streams_pictures';
        $data['max_file_size'] = 1200;
        $data['max_width'] = 2500;
        $data['max_height'] = 1400;
        $data['upload_to_module'] = true;
        return $data;
    }

    public function index(): void {
        $this->module('trongate_filezone');
        $this->module('localizations');

        $data['view_module'] = 'live_streams'; 
        $data['t'] = $this->localizations->_translator(get_language());

        $live_streams = $this->model->query('
            SELECT * 
            FROM live_streams 
            WHERE live = true
            OR start_date_and_time BETWEEN NOW() - INTERVAL 40 MINUTE AND NOW() + INTERVAL 1 DAY
            ORDER BY start_date_and_time DESC
          ', 
          'array'
        );

        if (empty($live_streams)) {
            $data['view_file'] = 'empty';
        } else {
            $data['view_file'] = 'live_streams';
            $data['streams'] = $live_streams;

            if (is_array($live_streams)) {
                $pictures_dir = __DIR__.'/../assets/live_streams_pictures/';
                $pictures_path = "live_streams_module/live_streams_pictures";

                foreach ($data['streams'] as &$stream) {
                    $stream['pictures'] = [];

                    if (is_dir($dir = $pictures_dir.$stream['id'])) {
                        $pictures = new FilesystemIterator($dir, FilesystemIterator::SKIP_DOTS);

                        foreach ($pictures as $picture) {
                            $stream['pictures'][] = "$pictures_path/{$stream['id']}/{$picture->getFilename()}";
                        }
                    }
                }
            }

            $data['additional_includes_top'] = [
                'live_streams_module/css/live_streams.css'
            ];
        }

        $this->template('public', $data);
    }

    public function new(): void {
      $this->module('localizations');

      $data = $this->get_data_from_post();
      $t = $this->localizations->_translator(get_language());
         
      $submit = post('submit', true);

      if ((string)$submit !== '') {
        if ($submit === 'Submit') { 
          $this->validation->set_rules('title', 'Title', 'required|min_length[2]|max_length[255]');
          $this->validation->set_rules('description', 'Description', 'required|min_length[2]');
          $this->validation->set_rules('summary', 'Summary', 'min_length[2]|max_length[255]');
          $this->validation->set_rules('start_date_and_time', 'Start Date And Time', 'required|valid_datetimepicker_us');
          $this->validation->set_rules('ingest', 'Ingest', 'min_length[2]|max_length[255]');
          $this->validation->set_rules('playlist', 'Playlist', 'min_length[2]|max_length[255]');

          $validated = $this->validation->run();

          if ($validated === true) {
            $data['start_date_and_time'] = str_replace(' at ', '', $data['start_date_and_time']);
            $data['start_date_and_time'] = date('Y-m-d H:i', strtotime($data['start_date_and_time']));
            $data['live'] = $data['live'] !== '';        
            $this->model->insert($data, 'live_streams');
            set_flashdata($t('Live stream created.'));

            redirect('live_streams');
            return;
          }
        }
      }

      $data['headline'] = $t('Create live stream');
      $data['cancel_url'] = BASE_URL.'live_streams';

      $data['form_location'] = BASE_URL.'live_streams/new';
      $data['view_file'] = 'create';
      $data['additional_includes_top'] = [
        'css/trongate-datetime.css'
      ];
      $data['additional_includes_btm'] = [
        'js/trongate-datetime.js' 
      ];
      $data['t'] = $t; 
      $this->template('public', $data);
    }

    public function start(): void {
        $update_id = (int) segment(3);
        $data = $this->get_data_from_db($update_id);
        $data['view_file'] = 'go_live';
        $data['additional_includes_top'] = [
            'live_streams_module/css/go_live.css'
        ];

        $this->template('public', $data);
    }

    public function webrtc(): void {
        $update_id = (int) segment(3);
        $data = $this->get_data_from_db($update_id);

        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        $this->module('localizations');
        $t = $this->localizations->_translator(get_language());

        if ($data['live'] === 1) {
            http_response_code(403);
            echo json_encode(['message' => $t('Live stream is already live.')]);
        } else {
            http_response_code(200);
            echo json_encode(['message' => $t('Live stream started.')]);

            $this->model->update($update_id, ['live' => 1]);
        }
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
            $data['headline'] = 'Update Live Stream Record';
            $data['cancel_url'] = BASE_URL.'live_streams/show/'.$update_id;
        } else {
            $data['headline'] = 'Create New Live Stream Record';
            $data['cancel_url'] = BASE_URL.'live_streams/manage';
        }

        $data['form_location'] = BASE_URL.'live_streams/submit/'.$update_id;
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
            $params['title'] = '%'.$searchphrase.'%';
            $params['summary'] = '%'.$searchphrase.'%';
            $params['ingest'] = '%'.$searchphrase.'%';
            $params['playlist'] = '%'.$searchphrase.'%';
            $sql = 'select * from live_streams
            WHERE title LIKE :title
            OR summary LIKE :summary
            OR ingest LIKE :ingest
            OR playlist LIKE :playlist
            ORDER BY id desc';
            $all_rows = $this->model->query_bind($sql, $params, 'object');
        } else {
            $data['headline'] = 'Manage Live Streams';
            $all_rows = $this->model->get('id desc');
        }

        $pagination_data['total_rows'] = count($all_rows);
        $pagination_data['page_num_segment'] = 3;
        $pagination_data['limit'] = $this->get_limit();
        $pagination_data['pagination_root'] = 'live_streams/manage';
        $pagination_data['record_name_plural'] = 'live streams';
        $pagination_data['include_showing_statement'] = true;
        $data['pagination_data'] = $pagination_data;

        $data['rows'] = $this->reduce_rows($all_rows);
        $data['selected_per_page'] = $this->get_selected_per_page();
        $data['per_page_options'] = $this->per_page_options;
        $data['view_module'] = 'live_streams';
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
            redirect('live_streams/manage');
        }

        $data = $this->get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data === false) {
            redirect('live_streams/manage');
        } else {
            $data['update_id'] = $update_id;
            $data['headline'] = 'Live Stream Information';
            $data['filezone_settings'] = $this->_init_filezone_settings();
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

            $this->validation->set_rules('title', 'Title', 'required|min_length[2]|max_length[255]');
            $this->validation->set_rules('description', 'Description', 'required|min_length[2]');
            $this->validation->set_rules('summary', 'Summary', 'min_length[2]|max_length[255]');
            $this->validation->set_rules('start_date_and_time', 'Start Date And Time', 'required|valid_datetimepicker_us');
            $this->validation->set_rules('ingest', 'Ingest', 'min_length[2]|max_length[255]');
            $this->validation->set_rules('playlist', 'Playlist', 'min_length[2]|max_length[255]');

            $result = $this->validation->run();

            if ($result === true) {

                $update_id = (int) segment(3);
                $data = $this->get_data_from_post();
                $data['start_date_and_time'] = str_replace(' at ', '', $data['start_date_and_time']);
                $data['start_date_and_time'] = date('Y-m-d H:i', strtotime($data['start_date_and_time']));
                $data['live'] = ($data['live'] === 1 ? 1 : 0);
                
                if ($update_id>0) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'live_streams');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'live_streams');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('live_streams/show/'.$update_id);

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
            $params['module'] = 'live_streams';
            $this->model->query_bind($sql, $params);

            //delete the record
            $this->model->delete($params['update_id'], 'live_streams');

            //set the flashdata
            $flash_msg = 'The record was successfully deleted';
            set_flashdata($flash_msg);

            //redirect to the manage page
            redirect('live_streams/manage');
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
        redirect('live_streams/manage');
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
        $record_obj = $this->model->get_where($update_id, 'live_streams');

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
        $data['title'] = post('title', true);
        $data['description'] = post('description', true);
        $data['summary'] = post('summary', true);
        $data['live'] = post('live', true);
        $data['start_date_and_time'] = post('start_date_and_time', true);
        $data['ingest'] = post('ingest', true);
        $data['playlist'] = post('playlist', true);        
        return $data;
    }

}
