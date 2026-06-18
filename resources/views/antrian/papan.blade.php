<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Antrian - Toko Buku</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { box-sizing: border-box; }
        body {
            background: #0b0514;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            overflow: hidden;
        }

        /* Header papan */
        .papan-header {
            background: linear-gradient(135deg, #da8cff, #9a55ff);
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(154, 85, 255, 0.2);
        }
        .papan-header-title {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: 1px;
        }
        .papan-header-right {
            color: rgba(255, 255, 255, 0.95);
            font-size: 0.9rem;
            text-align: right;
        }
        #jamSekarang {
            font-size: 1.45rem;
            font-weight: 800;
            color: #fff;
        }

        /* Main display */
        .papan-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .nomor-label {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.45);
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .nomor-besar {
            font-size: clamp(5rem, 20vw, 12rem);
            font-weight: 900;
            color: #fe7096;
            letter-spacing: 5px;
            line-height: 1;
            text-shadow: 0 0 50px rgba(254, 112, 150, 0.55);
            transition: all 0.4s ease;
            min-height: 1.2em;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nama-besar {
            font-size: clamp(1.8rem, 4.5vw, 3.2rem);
            font-weight: 800;
            color: #fff;
            margin-top: 20px;
            text-align: center;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
        }

        .silakan-text {
            font-size: clamp(1.1rem, 2vw, 1.6rem);
            color: rgba(255, 255, 255, 0.6);
            margin-top: 12px;
            font-weight: 500;
        }

        .divider {
            width: 100px;
            height: 4px;
            background: linear-gradient(135deg, #fe7096, transparent);
            border-radius: 2px;
            margin: 25px auto;
        }

        /* Antrian berikutnya */
        .papan-footer {
            background: #140b25;
            padding: 20px 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        .footer-label {
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 12px;
            font-weight: 700;
        }
        .antrian-next-list {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .next-badge {
            background: rgba(154, 85, 255, 0.1);
            border: 1px solid rgba(154, 85, 255, 0.3);
            color: #da8cff;
            border-radius: 10px;
            padding: 8px 18px;
            font-size: 0.95rem;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        /* Status bar */
        .status-bar {
            display: flex;
            gap: 25px;
            justify-content: center;
            margin-top: 20px;
        }
        .status-item {
            text-align: center;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 10px 20px;
            min-width: 120px;
        }
        .status-num {
            font-size: 1.6rem;
            font-weight: 800;
            color: #fe7096;
            text-shadow: 0 0 10px rgba(254, 112, 150, 0.2);
        }
        .status-lbl {
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .dot-live {
            width: 10px; height: 10px;
            background: #2ecc71;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
            animation: blink 1.5s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.2; }
        }

        .idle-text {
            color: rgba(255, 255, 255, 0.12);
            font-size: clamp(3rem, 10vw, 7rem);
            font-weight: 900;
            letter-spacing: 5px;
        }
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="papan-header">
    <div class="papan-header-title">
        <i class="bi bi-display me-2"></i>Papan Antrian
    </div>
    <div class="papan-header-right">
        <div><span class="dot-live"></span><small style="color:rgba(255, 255, 255, 0.9); font-weight:700;">LIVE</small></div>
        <div id="jamSekarang">00:00:00</div>
        <div id="tanggalSekarang" style="font-size:0.75rem;color:rgba(255,255,255,0.7); font-weight:550;"></div>
    </div>
</div>

{{-- MAIN DISPLAY --}}
<div class="papan-main">
    <div class="nomor-label">Nomor Antrian Dipanggil</div>

    <div class="nomor-besar" id="nomorBesar">
        <span class="idle-text">—</span>
    </div>

    <div class="nama-besar" id="namaBesar">Menunggu panggilan...</div>
    <div class="silakan-text" id="silakanText"></div>

    <div class="divider"></div>

    <div class="status-bar">
        <div class="status-item">
            <div class="status-num" id="statMenunggu">0</div>
            <div class="status-lbl">Menunggu</div>
        </div>
        <div class="status-item">
            <div class="status-num" id="statSelesai">0</div>
            <div class="status-lbl">Selesai</div>
        </div>
        <div class="status-item">
            <div class="status-num" id="statTerlambat">0</div>
            <div class="status-lbl">Terlambat</div>
        </div>
    </div>
</div>

{{-- FOOTER: Antrian Berikutnya --}}
<div class="papan-footer">
    <div class="footer-label"><i class="bi bi-people me-1"></i>Antrian Berikutnya</div>
    <div class="antrian-next-list" id="nextList">
        <span class="next-badge" style="color:rgba(255,255,255,0.3)">Tidak ada antrian</span>
    </div>
</div>

<script>
    let lastNomor = null;

    function bacakanAntrian(kode, nama){

        speechSynthesis.cancel();

        const teks =
            `Nomor antrian ${kode}.
            Atas nama ${nama}.
            Silakan menuju petugas pelayanan`;

        const suara =
            new SpeechSynthesisUtterance(teks);

        suara.lang = 'id-ID';
        suara.rate = 0.8;
        suara.pitch = 1;

        speechSynthesis.speak(suara);
    }

    // Jam realtime
    function updateJam() {
        const now = new Date();
        document.getElementById('jamSekarang').textContent =
            String(now.getHours()).padStart(2,'0') + ':' +
            String(now.getMinutes()).padStart(2,'0') + ':' +
            String(now.getSeconds()).padStart(2,'0');
        const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
        document.getElementById('tanggalSekarang').textContent =
            `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
    }
    setInterval(updateJam, 1000);
    updateJam();

    let lastDipanggil = null;
    let sudahPutar = false;

    function loadData() {

        fetch("{{ route('antrian.sse') }}")
        .then(res => res.json())
        .then(data => {

            document.getElementById('statMenunggu').innerHTML =
                data.menunggu.length;

            document.getElementById('statTerlambat').innerHTML =
                data.terlambat.length;

            document.getElementById('statSelesai').innerHTML =
                data.total_selesai;

            if(data.dipanggil){

                document.getElementById('nomorBesar').innerHTML =
                    data.dipanggil.kode_antrian;

                document.getElementById('namaBesar').innerHTML =
                    data.dipanggil.nama_pengunjung;

                document.getElementById('silakanText').innerHTML =
                    'Silakan menuju petugas pelayanan';

                if(lastNomor !== data.dipanggil.kode_antrian){

                    lastNomor =
                        data.dipanggil.kode_antrian;

                    bacakanAntrian(
                        data.dipanggil.kode_antrian,
                        data.dipanggil.nama_pengunjung
                    );
                }

            } else {

                document.getElementById('nomorBesar').innerHTML =
                    '<span class="idle-text">—</span>';

                document.getElementById('namaBesar').innerHTML =
                    'Menunggu panggilan...';

                document.getElementById('silakanText').innerHTML =
                    '';
            }

        });
    }

    loadData();

    setInterval(loadData, 3000);

    function panggilSuara(kode, nama) {
        const audio = new Audio("{{ asset('assets/audio/dingdong.mp3') }}");
        audio.play().then(() => {
            audio.onended = function() {
                bacakanTeks(kode, nama);
            };
        }).catch(() => {
            bacakanTeks(kode, nama);
        });
    }

    function bacakanTeks(kode, nama) {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            const teks = `Nomor antrian ${kode.split('').join(' ')}. ${nama}. Silakan menuju meja pelayanan.`;
            const utt = new SpeechSynthesisUtterance(teks);
            utt.lang = 'id-ID';
            utt.rate = 0.85;
            utt.pitch = 1;
            utt.volume = 1;

            function speak() {
                const voices = window.speechSynthesis.getVoices();
                const idVoice = voices.find(v => v.lang.startsWith('id'));
                if (idVoice) utt.voice = idVoice;
                window.speechSynthesis.speak(utt);
            }

            if (window.speechSynthesis.getVoices().length === 0) {
                window.speechSynthesis.onvoiceschanged = speak;
            } else {
                speak();
            }
        }
    }
</script>

</body>
</html>
