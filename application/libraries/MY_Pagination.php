<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * CodeIgniter Pagination Class (modified by >^o.o^<)
 *
 * Добавлен метод pagination_data,
 * который возвращает значения параметров исходя из входящих аргументов
 *
 */

class MY_Pagination extends CI_Pagination
{

    function __construct()
    {
        parent::__construct();
    }

    // возвращает массив с данными пагинации
    // @arg: total_rows - кол-во строк
    //       per_page   - кол-во строк на странице
    //       cur_page   - текущая страница, если не задана то по умолчанию первая
    // @return: array - total_rows - кол-во строк
    //                  per_page   - кол-во строк на странице
    //                  cur_page   - текущая страница
    //                  total_page - всего страниц
    //                  first_page - первая страница
    //                  end_pаge   - последняя страница
    //                  prev_page  - предыдущая страница
    //                  next_page  - следующая страница
    //                  prev_rows  - кол-во строк до текущей страницы
    //                  next_rows  - кол-во строк после текущей страницы
    //                  total_prev_page  - кол-во всего страниц до текущей
    //                  total_next_page  - кол-во всего страниц после текущей
    function pagination_data($total_rows, $per_page, $cur_page = ''){
        //echo $total_rows;

        if(is_numeric($total_rows) && !empty($per_page) && is_numeric($per_page)){
          $data['total_rows'] = $total_rows;
          $data['per_page'] = $per_page;
          // всего страниц
          $data['total_page'] = ceil($total_rows/$per_page);
          // текущая страница
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
          // первая страница
          $data['first_page'] = 1;
          // последняя страница
          $data['end_page'] = $data['total_page'];
          // предыдущая страница
          if(($data['cur_page'] - 1) < $data['first_page']){
            $data['prev_page'] = '';
          }else{
            $data['prev_page'] = $data['cur_page'] - 1;
          }
          // следующая страница
          if(($data['cur_page'] + 1) > $data['total_page']){
            $data['next_page'] = '';
          }else{
            $data['next_page'] = $data['cur_page'] + 1;
          }
          //кол-во строк до текущей страницы
          $data['prev_rows'] = (($data['cur_page'] - 1) * $data['per_page']);
          //кол-во строк до текущей страницы
          $next_cur_rows = $data['total_rows'] - $data['prev_rows'];
          if($next_cur_rows <= $data['total_rows']){
              $data['next_rows'] = 0;
          }else{
              $data['next_rows'] = $next_cur_rows + $data['per_page'];
          }
          //кол-во предыдущих страниц
          $data['total_prev_page'] = $data['cur_page'] - 1;
          //кол-во предыдущих страниц
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
