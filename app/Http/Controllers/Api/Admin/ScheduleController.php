<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Availability;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function store(Request $request)
    {
        $action = $request->input('action');

        if ($action == 'create') {
            $s = Schedule::create(['name' => $request->input('name')]);
            return response()->json(['success' => true, 'id' => $s->id]);
        }

        if ($action == 'rename') {
            $s = Schedule::find($request->input('id'));
            if ($s)
                $s->update(['name' => $request->input('name')]);
            return response()->json(['success' => !!$s]);
        }

        if ($action == 'set_default') {
            Schedule::where('id', '>', 0)->update(['is_default' => false]);
            $s = Schedule::find($request->input('id'));
            if ($s)
                $s->update(['is_default' => true]);
            return response()->json(['success' => !!$s]);
        }

        if ($action == 'duplicate') {
            $src = Schedule::find($request->input('source_id'));
            if (!$src)
                return response()->json(['success' => false]);

            $new = $src->replicate();
            $new->name = $src->name . ' (Copy)';
            $new->is_default = false;
            $new->save();

            // Copy Avail
            $avails = Availability::where('schedule_id', $src->id)->get();
            foreach ($avails as $a) {
                $nA = $a->replicate();
                $nA->schedule_id = $new->id;
                $nA->save();
            }

            return response()->json(['success' => true, 'id' => $new->id]);
        }

        return response()->json(['success' => false]);
    }

    public function destroy(Request $request)
    {
        $id = $request->query('id');
        Schedule::destroy($id);
        Availability::where('schedule_id', $id)->delete();
        return response()->json(['success' => true]);
    }
}
