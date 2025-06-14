<!-- <?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_acara');
            $table->string('lokasi_acara');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->time('awal_jam_pinjem');
            $table->time('akhir_jam_pinjem');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('peminjamans');
    }
};
// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     /**
//      * Run the migrations.
//      */
//     public function up(): void
//     {
//         Schema::create('peminjaman', function (Blueprint $table) {
//             $table->id();
//             $table->unsignedBigInteger('barang_id');
//             $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
//             $table->unsignedBigInteger('user_id');
//             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
//             $table->unsignedBigInteger('admin_id');
//             $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
//             $table->integer('jumlah');
//             $table->string('nama_acara', 255);
//             $table->string('lokasi_acara', 255);
//             $table->date('tanggal_pinjam');
//             $table->date('tanggal_kembali');
//             $table->time('awal_jam_pinjem');
//             $table->time('akhir_jam_pinjem');
//             $table->timestamps();
//         });
//         Schema::table('peminjaman', function (Blueprint $table) {
//     $table->dropColumn(['barang_id', 'jumlah']);
// });

//     }

//     /**
//      * Reverse the migrations.
//      */
//     public function down(): void
//     {
//         Schema::dropIfExists('peminjaman');
//     }
// }; -->
