<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter DomPDF Library
 *
 * Generate PDF's from HTML in CodeIgniter
 *
 * @packge        CodeIgniter
 * @subpackage        Libraries
 * @category        Libraries
 * @author        Ardianta Pargo
 * @license        MIT License
 * @link        https://github.com/ardianta/codeigniter-dompdf
 */
use Dompdf\Dompdf;
class Pdfgenerator extends Dompdf{
    /**
     * PDF filename
     * @var String
     */
    public $filename;
    public function __construct(){
        parent::__construct();
        $this->filename = "laporan.pdf";
    }
    /**
     * Get an instance of CodeIgniter
     *
     * @access    protected
     * @return    void
     */
    protected function ci()
    {
        return get_instance();
    }
    /**
     * Load a CodeIgniter view into domPDF
     *
     * @access    public
     * @param    string    $view The view to load
     * @param    array    $data The view data
     * @return    void
     */
	public function generate($html, $filename='', $stream=TRUE, $paper='A4', $orientation='portrait') {
        $dompdf = new Dompdf();

        // Mengatur ukuran dan orientasi kertas
        $dompdf->setPaper($paper, $orientation);

        // Load HTML
        $dompdf->loadHtml($html);

        // Render HTML ke PDF
        $dompdf->render();

        // Jika ingin mengunduh sebagai file
        if ($stream) {
            $dompdf->stream($filename.'.pdf', array('Attachment' => 0));
        } else {
            // Mengembalikan PDF sebagai string
            return $dompdf->output();
        }
    }
}
