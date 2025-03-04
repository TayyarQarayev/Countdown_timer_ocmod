<?php
namespace Opencart\Admin\Controller\Extension\CountdownTimer\Module;

class CountdownTimer extends \Opencart\System\Engine\Controller
{
    public function index()
    {
        $this->load->language('extension/countdown_timer/module/countdown_timer');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
        ];

        if (!isset($this->request->get['module_id'])) {
            $data['breadcrumbs'][] = [
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/countdown_timer/module/countdown_timer', 'user_token=' . $this->session->data['user_token'])
            ];
        } else {
            $data['breadcrumbs'][] = [
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/countdown_timer/module/countdown_timer', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'])
            ];
        }

        if (!isset($this->request->get['module_id'])) {
            $data['save'] = $this->url->link('extension/countdown_timer/module/countdown_timer|save', 'user_token=' . $this->session->data['user_token']);
        } else {
            $data['save'] = $this->url->link('extension/countdown_timer/module/countdown_timer|save', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id']);
        }

        $data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

        if (isset($this->request->get['module_id'])) {
            $this->load->model('setting/module');
            $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
        }

        if (isset($module_info['name'])) {
            $data['name'] = $module_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($module_info['start_time'])) {
            $data['start_time'] = $module_info['start_time'];
        } else {
            $data['start_time'] = '';
        }

        if (isset($module_info['end_time'])) {
            $data['end_time'] = $module_info['end_time'];
        } else {
            $data['end_time'] = '';
        }

        if (isset($module_info['status'])) {
            $data['status'] = $module_info['status'];
        } else {
            $data['status'] = '';
        }

        if (isset($this->request->get['module_id'])) {
            $data['module_id'] = (int) $this->request->get['module_id'];
        } else {
            $data['module_id'] = 0;
        }

        if (isset($tis->request->get['start_time'])) {
            $data['start_time'] = $this->request->get['start_time'];
        } else {
            $data['start_time'] = '';
        }

        if (isset($tis->request->get['end_time'])) {
            $data['end_time'] = $this->request->get['end_time'];
        } else {
            $data['end_time'] = '';
        }

        $data['user_token'] = $this->session->data['user_token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/countdown_timer/module/countdown_timer', $data));
    }
    public function save()
    {
        $this->load->language('extension/countdown_timer/module/countdown_timer');

        $json = [];

        if (!$this->user->hasPermission('modify', 'extension/countdown_timer/module/countdown_timer')) {
            $json['error']['warning'] = $this->language->get('error_permission');
        }

        // $log = new \Opencart\System\Library\Log('countdown_debug.log');
        // Admin paneldən gələn `end_time` dəyərini log-a yaz
        // $log->write('Admin Panelindən gələn məlumatlar: ' . json_encode($this->request->post));

        if (!$json) {
            $this->load->model('setting/module');

            if (!$this->request->post['module_id']) {
                $json['module_id'] = $this->model_setting_module->addModule('countdown_timer.countdown_timer', $this->request->post);
            } else {
                $this->model_setting_module->editModule($this->request->post['module_id'], $this->request->post);
            }

            $this->config->set('module_countdown_timer_id', $this->request->post['module_id']);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}