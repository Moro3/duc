<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Help_request_pagination extends CI_Driver
{

     // ���������� ������ � ������� ���������
    // @arg: total_rows - ���-�� �����
    //       per_page   - ���-�� ����� �� ��������
    //       cur_page   - ������� ��������, ���� �� ������ �� �� ��������� ������
    // @return: array - total_rows - ���-�� �����
    //                  per_page   - ���-�� ����� �� ��������
    //                  cur_page   - ������� ��������
    //                  total_page - ����� �������
    //                  first_page - ������ ��������
    //                  end_p�ge   - ��������� ��������
    //                  prev_page  - ���������� ��������
    //                  next_page  - ��������� ��������
    //                  prev_rows  - ���-�� ����� �� ������� ��������
    //                  next_rows  - ���-�� ����� ����� ������� ��������
    //                  total_prev_page  - ���-�� ����� ������� �� �������
    //                  total_next_page  - ���-�� ����� ������� ����� �������
    function data($total_rows, $per_page, $cur_page = ''){
        //var_dump($total_rows);
        //exit;
        if(is_numeric($total_rows) && !empty($per_page) && is_numeric($per_page)){
          $data['total_rows'] = $total_rows;
          $data['per_page'] = $per_page;
          // ����� �������
          $data['total_page'] = ceil($total_rows/$per_page);
          // ������� ��������
          if(!empty($cur_page)){
            if($cur_page > $data['total_page']) {
              $data['cur_page'] = $data['total_page'];
            }elseif($cur_page < 1){
              $data['cur_page'] = 1;
            }else{
              $data['cur_page'] = $cur_page;
            }
          }else{
            $data['cur_page'] = 1;
          }
          // ������ ��������
          $data['first_page'] = 1;
          // ��������� ��������
          $data['end_page'] = $data['total_page'];
          // ���������� ��������
          if(($data['cur_page'] - 1) < $data['first_page']){
            $data['prev_page'] = '';
          }else{
            $data['prev_page'] = $data['cur_page'] - 1;
          }
          // ��������� ��������
          if(($data['cur_page'] + 1) > $data['total_page']){
            $data['next_page'] = '';
          }else{
            $data['next_page'] = $data['cur_page'] + 1;
          }
          //���-�� ����� �� ������� ��������
          $data['prev_rows'] = (($data['cur_page'] - 1) * $data['per_page']);
          //���-�� ����� ����� ������� ��������
          $next_cur_rows = $data['total_rows'] - $data['prev_rows'] - $data['per_page'];
          if($next_cur_rows == $data['total_rows']){
              $data['next_rows'] = 0;
          }else{
              $data['next_rows'] = $next_cur_rows;
          }
          //���-�� ���������� �������
          $data['total_prev_page'] = $data['cur_page'] - 1;
          //���-�� ���������� �������
          $data['total_next_page'] = $data['total_page'] - $data['cur_page'];

        }else{
          $data['total_rows'] = 0;
          $data['total_page'] = 0;
          $data['cur_page'] = 1;
          $data['first_page'] = 1;
          $data['end_page'] = 1;
          $data['prev_page'] = '';
          $data['next_page'] = '';
          $data['per_page'] = 0;
          $data['prev_rows'] = 0;
          $data['next_rows'] = 0;
          $data['total_prev_page'] = 0;
          $data['total_next_page'] = 0;
        }
        if(isset($data)) return $data;
        return false;
    }
}