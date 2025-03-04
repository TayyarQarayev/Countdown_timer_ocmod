<?php
namespace Opencart\Catalog\Controller\Extension\CountdownTimer\Module;

class CountdownTimer extends \Opencart\System\Engine\Controller
{
    public function index(): string
    {
        $this->load->language('extension/countdown_timer/module/countdown_timer');

        $module_id = $this->config->get('module_countdown_timer_id');

        if ($module_id) {
            $this->load->model('setting/module');
            $module_info = $this->model_setting_module->getModule($module_id);
        }

        $data['start_time'] = $module_info['start_time'] ?? '';
        $data['end_time'] = $module_info['end_time'] ?? '';

        if (empty($data['start_time'])) {
            $data['start_time'] = date('Y-m-d H:i:s');
        }

        if (empty($data['end_time'])) {
            $data['end_time'] = date('Y-m-d H:i:s', strtotime('+1 day'));
        }

        return $this->load->view('extension/countdown_timer/module/countdown_timer', $data);
    }
}