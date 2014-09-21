<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter Profiler Class (modified by >^o.o^<)
 *
 * This class enables you to display benchmark, query, and other data
 * in order to help with debugging and optimization.
 *
 * Note: At some point it would be good to move all the HTML in this class
 * into a set of template files in order to allow customization.
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Libraries
 * @author      ExpressionEngine Dev Team, >^o.o^<
 * @link        http://codeigniter.com/user_guide/general/profiling.html
 */
class MY_Profiler extends CI_Profiler
{
    function __construct()
    {
        parent::__construct();
    }
    /**
     * Compile Queries (added total DB queries time)
     *
     * @access  private
     * @return  string
     */
    function _compile_queries()
    {
        $dbs = array();

        // Let's determine which databases are currently connected to
        foreach (get_object_vars($this->CI) as $CI_object)
        {
            if ( is_subclass_of(get_class($CI_object), 'CI_DB') )
            {
                $dbs[] = $CI_object;
            }
        }

        if (count($dbs) == 0)
        {
            $output  = "\n\n";
            $output .= '<fieldset style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
            $output .= "\n";
            $output .= '<legend style="color:#0000FF;">&nbsp;&nbsp;'.$this->CI->lang->line('profiler_queries').'&nbsp;&nbsp;</legend>';
            $output .= "\n";
            $output .= "\n\n<table cellpadding='4' cellspacing='1' border='0' width='100%'>\n";
            $output .="<tr><td width='100%' style='color:#0000FF;font-weight:normal;background-color:#eee;'>".$this->CI->lang->line('profiler_no_db')."</td></tr>\n";
            $output .= "</table>\n";
            $output .= "</fieldset>";

            return $output;
        }

        // Load the text helper so we can highlight the SQL
        $this->CI->load->helper('text');

        // Key words we want bolded
        $highlight = array('SELECT', 'DISTINCT', 'FROM', 'WHERE', 'AND', 'LEFT&nbsp;JOIN', 'ORDER&nbsp;BY', 'GROUP&nbsp;BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE', 'OR', 'HAVING', 'OFFSET', 'NOT&nbsp;IN', 'IN', 'LIKE', 'NOT&nbsp;LIKE', 'COUNT', 'MAX', 'MIN', 'ON', 'AS', 'AVG', 'SUM', '(', ')');

        $output  = "\n\n";

        foreach ($dbs as $db)
        {
            $output .= '<fieldset style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
            $output .= "\n";
            $output .= '<legend style="color:#0000FF;">&nbsp;&nbsp;'.$this->CI->lang->line('profiler_database').':&nbsp; '.$db->database.'&nbsp;&nbsp;&nbsp;'.$this->CI->lang->line('profiler_queries').': '.count($this->CI->db->queries).'&nbsp;&nbsp;&nbsp;</legend>';
            $output .= "\n";
            $output .= "\n\n<table cellpadding='4' cellspacing='1' border='0' width='100%'>\n";

            if (count($db->queries) == 0)
            {
                $output .= "<tr><td width='100%' style='color:#0000FF;font-weight:normal;background-color:#eee;'>".$this->CI->lang->line('profiler_no_queries')."</td></tr>\n";
            }
            else
            {
                $total_time = 0.0;
                foreach ($db->queries as $key => $val)
                {
                    $time = number_format($db->query_times[$key], 4);

                    $val = highlight_code($val, ENT_QUOTES);

                    foreach ($highlight as $bold)
                    {
                        $val = str_replace($bold, '<strong>'.$bold.'</strong>', $val);
                    }

                    $output .= "<tr><td width='1%' valign='top' style='color:#990000;font-weight:normal;background-color:#ddd;'>".$time."&nbsp;&nbsp;</td><td style='color:#000;font-weight:normal;background-color:#ddd;'>".$val."</td></tr>\n";
                    $total_time += $db->query_times[$key];
                }
                $output .= "<tr><td width='1%' valign='top' style='color:#990000;font-weight:normal;background-color:#ddd;'>" . number_format($total_time, 4) . "&nbsp;&nbsp;</td><td style='color:#000;font-weight:normal;background-color:#ddd;'>Total queries time</td></tr>\n";
            }

            $output .= "</table>\n";
            $output .= "</fieldset>";

        }

        return $output;
    }

    public function _compile_session_data()
    {
        $output  = "<style>pre { margin:0; }</style>\n\n";
        if (!isset($this->CI->session) || !is_object($this->CI->session))
        {
            $output .= '<fieldset style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
            $output .= "\n";
            $output .= '<legend style="color:#0000FF;">&nbsp;&nbsp;Session data&nbsp;&nbsp;</legend>';
            $output .= "\n";
            $output .= "\n\n<table cellpadding='4' cellspacing='1' border='0' width='100%'>\n";
            $output .="<tr><td width='100%' style='color:#0000FF;font-weight:normal;background-color:#eee;'>Session class NOT initialised</td></tr>\n";
            $output .= "</table>\n";
            $output .= "</fieldset>";

            return $output;
        }
        else
        {
            $output .= '<fieldset style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
            $output .= "\n";
            $output .= '<legend style="color:#0000FF;">&nbsp;&nbsp;Session info&nbsp;&nbsp;&nbsp;</legend>';
            $output .= "\n";
            $output .= "\n\n<table cellpadding='4' cellspacing='1' border='0' width='100%'>\n";

            $data = $this->CI->session->all_userdata();
            if (count($data) == 0)
            {
                $output .= "<tr><td width='100%' style='color:#0000FF;font-weight:normal;background-color:#eee;'>Empty session</td></tr>\n";
            }
            else
            {
                foreach ($data as $key => $val)
                {
                    $output .= "<tr><td width='1%' valign='top' style='color:#990000;font-weight:normal;background-color:#ddd;'>".$key."&nbsp;&nbsp;</td><td style='color:#000;font-weight:normal;background-color:#ddd;'><pre>(" . gettype($val) . ") " . var_export($val, TRUE);
                    $output .= "</pre></td></tr>\n";
                }
            }
            $output .= "</table>\n";
            $output .= "</fieldset>";

            return $output;
        }
    }

    /**
     * Run the Profiler (added session data)
     *
     * @access  private
     * @return  string
     */
    function run()
    {
        $output = "<div id='codeigniter_profiler' style='clear:both;background-color:#fff;padding:10px;'>";

        $output .= $this->_compile_uri_string();
        $output .= $this->_compile_controller_info();
        $output .= $this->_compile_memory_usage();
        $output .= $this->_compile_benchmarks();
        $output .= $this->_compile_get();
        $output .= $this->_compile_post();
        $output .= $this->_compile_session_data();
        $output .= $this->_compile_queries();

        $output .= '</div>';

        return $output;
    }
}
/* EoF libraries/MY_Profiler.php */