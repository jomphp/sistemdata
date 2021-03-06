<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\User;

class UsersController extends Controller
{
    public function index()
    {
      // Dapatkan data SEMUA users dari table users
      // $senarai_users = DB::table('users')->get();

      $senarai_users = User::paginate(3);

      // return view('users/senarai')
      // dan paparkan sekali result senarai_user
      return view('users/senarai', compact('senarai_users') );
    }

    public function create()
    {
      return view('users/borang_tambah');
    }

    public function store( Request $request )
    {
      // Lakukan validation terhadap input dari borang
      // Format code $this->validate( $request, $array_rules );
      $this->validate( $request, [
        'username' => 'required|min:3',
        'email' => 'required|email',
        'password' => 'required|min:5',
        'nama' => 'required',
        'status' => 'required|in:administrator,user',
        'negeri' => 'required',
        'unit' => 'required'
      ] );

      // Dapatkan KESEMUA data dari borang
      $data = $request->except('_token', 'password');

      // Tambah array password ke variable $data dimana
      // Password perlu di encrypt terlebih dahulu
      $data['password'] = bcrypt( $request->input('password') );

      // Simpan data ke dalam database
      // DB::table('users')->insert( $data );
      User::insert( $data );

      // Redirect user ke halaman senarai users
      return redirect('users');

    }

    public function edit($id)
    {
      // Dapatkan data dari table users
      // berdasarkan ID yang diberi
      // $user = DB::table('users')
      // ->where('id', '=', $id)
      // ->first();
      // $user = DB::table('users')->find($id);
      $user = User::find($id);

      return view('users/borang_edit', compact('user') );
    }

    public function update( Request $request, $id )
    {
      $this->validate( $request, [
        'username' => 'required|min:3',
        'email' => 'required|email',
        'nama' => 'required',
        'status' => 'required|in:administrator,user',
        'negeri' => 'required',
        'unit' => 'required'
      ] );

      // Dapatkan KESEMUA data dari borang
      $data = $request->except('_token', '_method', 'password');

      // Semak adakah password perlu dikemaskini
      // Jika field password tidak kosong, update password baru
      if ( ! empty( $request->input('password') ) )
      {
        $data['password'] = bcrypt( $request->input('password') );
      }

      // Simpan rekod kemaskini ke table users berdasarkan ID user
      // DB::table('users')->where('id', '=', $id)->update( $data );
      User::find($id)->update( $data );

      // Kembali ke halaman senarai users
      return redirect('users');

    }

    public function destroy($id)
    {
      // Cari user berdasarkan ID dan hapuskan dari table users
      // DB::table('users')->where('id', '=', $id)->delete();
      User::find($id)->delete();

      // Kembali ke senarai user
      return redirect('users');
    }
}
