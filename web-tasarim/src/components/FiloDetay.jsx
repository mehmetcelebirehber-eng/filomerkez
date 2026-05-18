import React from 'react';
import { motion } from 'framer-motion';
import { ArrowLeft, Users, Wifi, Wind, Coffee, ShieldCheck, Tv, Bus, Compass, Map, Star } from 'lucide-react';

const fleetData = {
  otobus: {
    title: 'Otobüs Filomuz',
    desc: 'Şehirlerarası ve uluslararası standartlarda, maksimum konfor ve güvenlik donanımına sahip lüks otobüslerimiz.',
    vehicles: [
      {
        name: 'Mercedes-Benz Travego',
        seats: '46+1+1 / 54+1+1',
        image: '/images/fleet/otobus.png', // Fallback to existing image
        features: ['Geniş Diz Mesafesi', 'Kişisel Eğlence Ekranı (10 inç)', '220V Priz & USB', 'Ergonomik Deri Koltuklar', 'Gelişmiş İklimlendirme', 'Araç İçi Buzdolabı & Çay/Kahve Makinesi'],
        highlights: [Wind, Tv, Coffee]
      },
      {
        name: 'Mercedes-Benz Tourismo',
        seats: '50+1+1 / 54+1+1',
        image: '/images/fleet/otobus.png',
        features: ['AEBS (Acil Fren Sistemi)', 'Şerit Takip Asistanı', 'Wi-Fi Bağlantısı', 'Geniş Bagaj Hacmi', 'Özel Okuma Lambaları', 'Akıllı Klima Sistemi'],
        highlights: [ShieldCheck, Wifi, Users]
      },
      {
        name: 'Neoplan Tourliner',
        seats: '50+1+1',
        image: '/images/fleet/otobus.png',
        features: ['Panoramik Camlar', 'Premium Koltuk Kumaşları', 'Çift Bölge İklimlendirme', 'Geniş Koridor', 'Aktif Süspansiyon Sistemi', 'LED Aydınlatma'],
        highlights: [Wind, Users, Coffee]
      },
      {
        name: 'Temsa Safir Plus',
        seats: '41+1+1 / 46+1+1',
        image: '/images/fleet/otobus.png',
        features: ['Geniş İç Hacim', 'Konforlu Seyahat Koltukları', 'Güçlü İklimlendirme', 'Ergonomik Tasarım', 'Ekstra Bagaj Kapasitesi', 'Kesintisiz Wi-Fi'],
        highlights: [Users, Wind, Wifi]
      },
      {
        name: 'MAN Fortuna',
        seats: '46+1+1 / 54+1+1',
        image: '/images/fleet/otobus.png',
        features: ['Yüksek Güvenlik Standartları', 'Geniş Diz Mesafesi', 'Okuma Lambaları', 'Güçlü Süspansiyon', 'İklimlendirme Sistemi', 'Sessiz Kabin'],
        highlights: [ShieldCheck, Wind, Coffee]
      }
    ]
  },
  midibus: {
    title: 'Midibüs Filomuz',
    desc: 'Orta ölçekli gruplar için tasarlanmış, kıvrak, konforlu ve ekonomik seyahat çözümleri.',
    vehicles: [
      {
        name: 'Otokar Sultan Mega',
        seats: '31+1+1',
        image: '/images/fleet/midibus.png',
        features: ['Geniş İç Hacim', 'Yatar Koltuklar', 'Güçlü Klima', 'Hostes Koltuğu', 'Geniş Bagaj', 'Okuma Lambası'],
        highlights: [Wind, Users, ShieldCheck]
      },
      {
        name: 'Isuzu Turkuaz',
        seats: '31+1',
        image: '/images/fleet/midibus.png',
        features: ['Ergonomik Koltuklar', 'Çift Cam İzolasyonu', 'Bağımsız Süspansiyon', 'Dijital Klima', 'TV/DVD Sistemi', 'Retarder'],
        highlights: [Tv, Wind, ShieldCheck]
      },
      {
        name: 'Temsa Prestij SX',
        seats: '29+1',
        image: '/images/fleet/midibus.png',
        features: ['Sessiz Motor', 'Kompakt Boyutlar', 'Yüksek Bagaj Hacmi', 'Kişisel Havalandırma', 'USB Şarj Çıkışları', 'Modern Kabin'],
        highlights: [Wind, Wifi, Users]
      },
      {
        name: 'Otokar Sultan Maxi',
        seats: '31+1+1',
        image: '/images/fleet/midibus.png',
        features: ['Yüksek Tavan', 'Gelişmiş Fren Sistemi', 'LED İç Aydınlatma', 'Dijital Gösterge', 'Geniş Diz Mesafesi', 'Ergonomik Tasarım'],
        highlights: [ShieldCheck, Users, Wind]
      },
      {
        name: 'Isuzu Novo Lux',
        seats: '27+1',
        image: '/images/fleet/midibus.png',
        features: ['Premium İç Dizayn', 'Gelişmiş İklimlendirme', 'Hava Süspansiyon', 'Turizm Tipi Koltuklar', 'Buzdolabı', 'LCD Ekran'],
        highlights: [Tv, Coffee, Wind]
      }
    ]
  },
  minibus: {
    title: 'Minibüs Filomuz',
    desc: 'Küçük gruplar, personel taşımacılığı ve butik turlar için ideal, seri ve donanımlı minibüslerimiz.',
    vehicles: [
      {
        name: 'Mercedes-Benz Sprinter',
        seats: '16+1 / 19+1',
        image: '/images/fleet/minibus.png',
        features: ['Gelişmiş Güvenlik Paketi', 'Otomatik Kapı Sistemi', 'Yüksek Tavan Ferahlığı', '3 Noktalı Emniyet Kemeri', 'Bağımsız Arka Klima'],
        highlights: [ShieldCheck, Wind, Users]
      },
      {
        name: 'Volkswagen Crafter',
        seats: '16+1 / 19+1',
        image: '/images/fleet/minibus.png',
        features: ['Konforlu Yolcu Koltukları', 'Sessiz Kabin', 'Gelişmiş Süspansiyon', 'USB Şarj Çıkışları', 'Yolcu Bölümü Kliması'],
        highlights: [Wind, Wifi, ShieldCheck]
      },
      {
        name: 'Ford Transit',
        seats: '16+1 / 17+1',
        image: '/images/fleet/minibus.png',
        features: ['Geniş İç Hacim', 'Kayar Basamak Sistemi', 'Bağımsız İklimlendirme', 'Kör Nokta Uyarı', 'Ergonomik Koltuklar'],
        highlights: [Users, ShieldCheck, Wind]
      },
      {
        name: 'Renault Master',
        seats: '16+1',
        image: '/images/fleet/minibus.png',
        features: ['Yüksek Tavan', 'Ekstra Geniş Camlar', 'Çift İklimlendirme', 'Süspansiyonlu Sürücü Koltuğu', 'LED Aydınlatma'],
        highlights: [Wind, Users, Tv]
      },
      {
        name: 'Fiat Ducato',
        seats: '16+1',
        image: '/images/fleet/minibus.png',
        features: ['Ekonomik Seyahat', 'Konforlu Kabin', 'Gelişmiş Havalandırma', 'Geniş Diz Mesafesi', 'Kişisel Şarj Portları'],
        highlights: [Wifi, Wind, Users]
      }
    ]
  },
  vip: {
    title: 'VIP Araç Filomuz',
    desc: 'Lüksün sınırlarını zorlayan, tamamen kişiye özel dizayn edilmiş ultra premium VIP araçlarımız.',
    vehicles: [
      {
        name: 'Mercedes-Benz Vito VIP',
        seats: '4+1 / 6+1',
        image: '/images/fleet/vip.png',
        features: ['Elektrikli Masajlı Koltuklar', 'Ara Bölme (TV\'li Asansörlü)', 'PlayStation / Apple TV', 'Minibar ve Kahve Makinesi', 'Yıldız Tavan Aydınlatması', 'Touchpad Kontrol Ünitesi'],
        highlights: [Tv, Coffee, Wifi]
      },
      {
        name: 'Mercedes-Benz Sprinter VIP',
        seats: '9+1 / 12+1',
        image: '/images/fleet/vip.png',
        features: ['Ultra Geniş Diz Mesafesi', 'Business Class Koltuklar', 'Çalışma Masası', 'Buzdolabı', 'Gelişmiş Ses Sistemi', 'Bağımsız Arka İklimlendirme'],
        highlights: [Wind, Tv, ShieldCheck]
      },
      {
        name: 'Volkswagen Caravelle VIP',
        seats: '8+1',
        image: '/images/fleet/vip.png',
        features: ['Ergonomik VIP Koltuklar', 'Bağımsız Dijital Klima', 'Gizli Çalışma Masaları', 'Premium Ses Sistemi', 'Karartılmış Camlar', 'Ambiyans Işıkları'],
        highlights: [Users, Wind, Wifi]
      },
      {
        name: 'Mercedes-Benz Maybach',
        seats: '3+1',
        image: '/images/fleet/vip.png',
        features: ['Ultra Lüks Deri Döşeme', 'Isıtmalı/Soğutmalı Koltuklar', 'Executive Masaj Fonksiyonu', 'Panoramik Cam Tavan', 'Şampanya Soğutucu', 'Burmester Ses Sistemi'],
        highlights: [Coffee, ShieldCheck, Tv]
      },
      {
        name: 'Range Rover Autobiography',
        seats: '3+1',
        image: '/images/fleet/vip.png',
        features: ['Executive Arka Koltuklar', 'Meridian Ses Sistemi', 'Elektrikli Yan Perdeler', 'Havalı Süspansiyon', 'Arka Multimedya Sistemi', 'Tam Sessiz Kabin'],
        highlights: [ShieldCheck, Tv, Wind]
      }
    ]
  },
  otomobil: {
    title: 'Otomobil Filomuz',
    desc: 'Bireysel kiralama, şoförlü tahsis ve protokol taşımacılığına uygun, D ve E segmenti prestijli otomobillerimiz.',
    vehicles: [
      {
        name: 'Mercedes-Benz E-Class',
        seats: '4+1',
        image: '/images/fleet/otomobil.png',
        features: ['Deri Döşeme', 'Isıtmalı Koltuklar', 'Sessiz Kabin', 'Ambiyans Aydınlatma', 'Aktif Fren Asistanı'],
        highlights: [ShieldCheck, Wind, Coffee]
      },
      {
        name: 'Volkswagen Passat',
        seats: '4+1',
        image: '/images/fleet/otomobil.png',
        features: ['ErgoComfort Koltuklar', 'Geniş Bagaj (586L)', 'Üç Bölgeli Dijital Klima', 'Yorgunluk Tespit Sistemi', 'Apple CarPlay'],
        highlights: [Wind, Wifi, ShieldCheck]
      },
      {
        name: 'Audi A6',
        seats: '4+1',
        image: '/images/fleet/otomobil.png',
        features: ['Valcona Deri Döşeme', 'Matrix LED Farlar', 'Sanal Kokpit', 'Premium Ses Sistemi', 'Dört Bölgeli Klima'],
        highlights: [ShieldCheck, Tv, Wind]
      },
      {
        name: 'BMW 5 Serisi',
        seats: '4+1',
        image: '/images/fleet/otomobil.png',
        features: ['Dakota Deri Koltuklar', 'İklimlendirmeli Koltuklar', 'Gelişmiş Sürüş Asistanı', 'Harman Kardon Müzik', 'Lüks Kabin Hissi'],
        highlights: [Wind, Users, ShieldCheck]
      },
      {
        name: 'Skoda Superb',
        seats: '4+1',
        image: '/images/fleet/otomobil.png',
        features: ['Devasa Arka Diz Mesafesi', '625L Bagaj Kapasitesi', 'Konforlu Süspansiyon', 'Şemsiye Gözleri', 'Sessiz Sürüş'],
        highlights: [Users, Wind, Wifi]
      }
    ]
  }
};

export default function FiloDetay({ category, onBack, onQuoteClick }) {
  const data = fleetData[category] || fleetData['otobus'];

  return (
    <motion.div 
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      exit={{ opacity: 0, y: -20 }}
      transition={{ duration: 0.5 }}
      style={{ background: '#f8fafc', minHeight: '100vh', paddingBottom: '100px' }}
    >
      {/* Hero Banner */}
      <div style={{
        background: 'linear-gradient(135deg, #0f172a 0%, #1e293b 100%)',
        padding: '120px 5% 80px',
        color: 'white',
        textAlign: 'center',
        position: 'relative',
        overflow: 'hidden'
      }}>
        {/* Background Gradients */}
        <div style={{ position: 'absolute', top: '-50%', left: '-10%', width: '60%', height: '200%', background: 'radial-gradient(circle, rgba(16,84,156,0.2) 0%, rgba(0,0,0,0) 70%)', transform: 'rotate(30deg)' }} />
        <div style={{ position: 'absolute', bottom: '-50%', right: '-10%', width: '60%', height: '200%', background: 'radial-gradient(circle, rgba(226,27,27,0.15) 0%, rgba(0,0,0,0) 70%)', transform: 'rotate(-30deg)' }} />

        {/* Animated Premium Icons Background */}
        <div style={{ position: 'absolute', top: 0, left: 0, width: '100%', height: '100%', overflow: 'hidden', zIndex: 0, pointerEvents: 'none' }}>
          {[
            { Icon: Bus, size: 140, x: '5%', y: '10%', delay: 0, duration: 25 },
            { Icon: Compass, size: 90, x: '25%', y: '60%', delay: 5, duration: 22 },
            { Icon: ShieldCheck, size: 120, x: '85%', y: '15%', delay: 2, duration: 20 },
            { Icon: Star, size: 70, x: '75%', y: '70%', delay: 7, duration: 18 },
            { Icon: Users, size: 160, x: '45%', y: '20%', delay: 4, duration: 28 },
            { Icon: Map, size: 100, x: '15%', y: '75%', delay: 10, duration: 24 }
          ].map((item, i) => (
            <motion.div
              key={i}
              style={{
                position: 'absolute',
                top: item.y,
                left: item.x,
                color: 'rgba(255, 255, 255, 0.04)',
                pointerEvents: 'none',
              }}
              animate={{ y: [0, -40, 0], x: [0, 30, 0], rotate: [0, 15, -10, 0] }}
              transition={{ duration: item.duration, repeat: Infinity, ease: 'easeInOut', delay: item.delay }}
            >
              <item.Icon size={item.size} strokeWidth={1} />
            </motion.div>
          ))}
        </div>

        <motion.button 
          onClick={onBack}
          whileHover={{ x: -5, background: 'rgba(255,255,255,0.2)' }}
          style={{ 
            position: 'absolute', top: '30px', left: '5%', 
            background: 'rgba(255,255,255,0.1)', border: '1px solid rgba(255,255,255,0.2)', 
            color: 'white', padding: '10px 24px', borderRadius: '50px', 
            display: 'flex', alignItems: 'center', gap: '8px', cursor: 'pointer',
            backdropFilter: 'blur(10px)', fontWeight: 600, transition: 'all 0.3s ease',
            zIndex: 99
          }}
        >
          <ArrowLeft size={18} /> Geri Dön
        </motion.button>

        <motion.div
          initial={{ opacity: 0, scale: 0.9 }}
          animate={{ opacity: 1, scale: 1 }}
          transition={{ delay: 0.2, duration: 0.5 }}
          style={{ position: 'relative', zIndex: 10 }}
        >
          <span style={{ display: 'inline-block', background: 'rgba(255,255,255,0.1)', border: '1px solid rgba(255,255,255,0.2)', padding: '6px 20px', borderRadius: '50px', fontSize: '0.85rem', fontWeight: 700, letterSpacing: '2px', marginBottom: '20px' }}>
            PREMIUM ARAÇ FİLOMUZ
          </span>
          <h1 style={{ fontSize: 'clamp(2.5rem, 5vw, 4rem)', fontWeight: 900, marginBottom: '20px', fontFamily: 'var(--font-heading)', textShadow: '0 4px 20px rgba(0,0,0,0.5)' }}>
            {data.title}
          </h1>
          <p style={{ fontSize: '1.2rem', color: 'rgba(255,255,255,0.85)', maxWidth: '700px', margin: '0 auto', lineHeight: 1.6 }}>
            {data.desc}
          </p>
        </motion.div>
      </div>

      {/* Vehicles List */}
      <div style={{ maxWidth: '1800px', margin: '-40px auto 0', padding: '0 3%', position: 'relative', zIndex: 10 }}>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(260px, 1fr))', gap: '20px' }}>
          {data.vehicles.map((v, index) => (
            <motion.div 
              key={index}
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.3 + (index * 0.1) }}
              style={{ 
                background: 'linear-gradient(135deg, #ffffff 0%, #f8fafc 50%, rgba(16,84,156,0.03) 100%)', 
                borderRadius: '24px', 
                boxShadow: '0 20px 40px rgba(0,0,0,0.06)', 
                overflow: 'hidden',
                display: 'flex',
                flexDirection: 'column',
                border: '1px solid rgba(16,84,156,0.08)'
              }}
            >
              {/* Image Container */}
              <div style={{ 
                width: '100%',
                height: '280px',
                background: 'linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%)',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                padding: '20px',
                position: 'relative'
              }}>
                <img 
                  src={v.image} 
                  alt={v.name} 
                  style={{ 
                    width: '90%', 
                    height: '100%', 
                    objectFit: 'contain',
                    filter: 'drop-shadow(0 15px 25px rgba(0,0,0,0.15))',
                    transform: 'scale(1.05)'
                  }} 
                />
              </div>

              {/* Details Container */}
              <div style={{ flex: '1', padding: '25px', display: 'flex', flexDirection: 'column', justifyContent: 'center' }}>
                <h2 style={{ fontSize: '1.3rem', fontWeight: 900, color: 'var(--color-text-primary)', marginBottom: '15px', fontFamily: 'var(--font-heading)' }}>
                  {v.name}
                </h2>
                
                <div style={{ display: 'flex', alignItems: 'center', gap: '10px', marginBottom: '15px', paddingBottom: '15px', borderBottom: '1px solid rgba(0,0,0,0.05)', flexWrap: 'wrap' }}>
                  <div style={{ 
                    display: 'flex', 
                    alignItems: 'center', 
                    gap: '12px', 
                    background: 'linear-gradient(135deg, #0f172a 0%, #1e293b 100%)', 
                    color: 'white', 
                    padding: '8px 16px', 
                    borderRadius: '50px', 
                    fontWeight: 700,
                    fontSize: '0.9rem',
                    boxShadow: '0 4px 10px rgba(15,23,42,0.15)'
                  }}>
                    <Users size={16} color="#94a3b8" />
                    <span style={{ color: '#94a3b8', fontWeight: 500 }}>Kapasite:</span> {v.seats}
                  </div>
                  <div style={{ display: 'flex', gap: '10px' }}>
                    {v.highlights.map((Icon, i) => (
                      <div key={i} style={{ background: '#f8fafc', padding: '8px', borderRadius: '10px', color: '#64748b' }}>
                        <Icon size={18} />
                      </div>
                    ))}
                  </div>
                </div>

                <div style={{ marginBottom: '20px' }}>
                  <h4 style={{ fontSize: '0.95rem', fontWeight: 700, color: 'var(--color-text-primary)', marginBottom: '10px' }}>Öne Çıkan Donanımlar</h4>
                  <ul style={{ 
                    listStyle: 'none', 
                    padding: 0, 
                    margin: 0, 
                    display: 'grid', 
                    gridTemplateColumns: '1fr', 
                    gap: '8px' 
                  }}>
                    {v.features.map((feature, i) => (
                      <li key={i} style={{ display: 'flex', alignItems: 'flex-start', gap: '8px', color: 'var(--color-text-secondary)', fontSize: '0.85rem', fontWeight: 500 }}>
                        <div style={{ width: '5px', height: '5px', borderRadius: '50%', background: 'var(--color-accent)', marginTop: '6px', flexShrink: 0 }} />
                        {feature}
                      </li>
                    ))}
                  </ul>
                </div>

                <motion.button 
                  onClick={onQuoteClick}
                  animate={{ 
                    backgroundPosition: ["0% 50%", "100% 50%", "0% 50%"],
                    boxShadow: ["0 10px 20px rgba(226,27,27,0.3)", "0 15px 30px rgba(16,84,156,0.4)", "0 10px 20px rgba(226,27,27,0.3)"]
                  }}
                  transition={{ 
                    duration: 3, 
                    repeat: Infinity, 
                    ease: "linear" 
                  }}
                  whileHover={{ scale: 1.05 }}
                  whileTap={{ scale: 0.95 }}
                  style={{ 
                    background: 'linear-gradient(270deg, #e21b1b, #10549c, #e21b1b)',
                    backgroundSize: '200% 200%',
                    color: 'white', 
                    border: 'none', 
                    padding: '12px 24px', 
                    borderRadius: '50px', 
                    fontSize: '0.9rem', 
                    fontWeight: 700, 
                    fontFamily: '"Inter", sans-serif',
                    letterSpacing: '1.5px',
                    textTransform: 'uppercase',
                    cursor: 'pointer', 
                    alignSelf: 'flex-start',
                    display: 'flex',
                    alignItems: 'center',
                    gap: '8px',
                    width: '100%',
                    justifyContent: 'center'
                  }}
                >
                  TEKLİF AL
                </motion.button>
              </div>
            </motion.div>
          ))}
        </div>
      </div>
    </motion.div>
  );
}
