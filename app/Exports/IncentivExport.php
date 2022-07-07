<?php

namespace App\Exports;
use App\Generatedincentive;
use App\Detailedreport;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
class IncentivExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
      public function __construct(int $id)
    {
        $this->id = $id;
    }
    public function headings(): array{
        return ['acc_id', 'bonus', 'type'];
    }
    public function collection()
    {
        //
         
          return Detailedreport::where('incentive_id', $this->id)->where('bonus', '!=', '0')->join('users', 'users.id', 'detailedreports.user_id')->select('payroll_id', DB::raw("SUM(bonus) as bonus"), 'type')->groupby('payroll_id', 'type')->get();
          
    }
}
