<?php
namespace Sprisimulator;

use Fuel\Core\Log;
use Fuel\Core\Package;
\Fuel\Core\Package::load('spriv4');
use Spri\Model\Spri\Auftrag;
use Fuel\Core\Presenter;
use Spri\Model\Spri;
use Spri\Model\Spri\Geschaeftsfall;
use Spri\Model\Spri\Provider;
use Spri\Model\Spri\Termin;
use Spri\Model\Spri\Geschaeftsfallmeldung;
use Spri\Model\Spri\Auftragsstatus;
use Spri\Model\Spri\Abmanschluss;

class Presenter_Soapsimulator_Auftragsstatus extends Presenter
{
    /**
     * Prepare the view data, keeping this in here helps clean up
     * the controller.
     *
     * @return void
     */
    public function view()
    {
        Log::error("Status Presenter: ".json_encode($_REQUEST));
        $this->interne_auftrags_id = null;
        $this->selected = -1;
        if(isset($_REQUEST['interne_auftrags_id']))
        {
            $this->interne_auftrags_id = $_REQUEST['interne_auftrags_id'];
            $this->selected = $_REQUEST['interne_auftrags_id'];
        }
        $this->h_auftragsstatus = 'Auftragsstatus';

        $this->arr_auftrag = Auftrag::find_all();
        $provider = Provider::find_all();
        $this->arr_provider = [];
        $this->arr_checks = [];
        $this->arr_messages = [];
        $this->arr_tickets = [];
        foreach ($provider as $item) {
            $this->arr_provider[$item[Provider::COLUMN_ID]] = $item[Provider::COLUMN_PV_NAME];
        }
        $this->arr_auftragstyp = ['10' => 'NEU', '20' => 'KUE-AG', '30' => 'KUE-LE', '40' => 'AEN-LMAE', '50' => 'LAE', '60' => 'PV', '70' => 'EST', '80' => 'SET', '90' => 'GET'];
        $this->arr_status = ['-' => 'secondary','zu_erledigen' => 'warning','erledigt_ok' => 'success','erledigt_error' => 'danger','in_arbeit' => 'info' ];
        if($this->interne_auftrags_id)
        {
            $this->selected_auftragschecks = Auftragsstatus::get_all_checks_by_auftrag_id($this->interne_auftrags_id);
            $this->selected_auftragsmsg = Auftragsstatus::get_all_msg_status_by_auftrag_id($this->interne_auftrags_id);
            $this->gf_meldungen_auftrag = Geschaeftsfallmeldung::get_all_by_auftrag_id($this->interne_auftrags_id);
            $this->termine_auftrag =  Termin::get_all_by_auftrag_id($this->interne_auftrags_id);
        }
    }


}
