import React from 'react';
import { motion } from 'framer-motion';
import { Users, Wifi, Wind, Coffee, ShieldCheck, Tv, ArrowRight, Bus, Compass, Star } from 'lucide-react';

const fleetData = [
  {
    id: 'otobus',
    title: 'Otobüs Filomuz',
    desc: 'Şehirlerarası ve uluslararası standartlarda, maksimum konfor ve güvenlik donanımına sahip lüks otobüslerimiz.',
    vehicles: [
      { name: 'Mercedes-Benz Travego', seats: '46+1 / 54+1', image: '/images/fleet/otobus.png', features: ['Geniş Diz Mesafesi', '220V Priz & USB', 'Gelişmiş İklimlendirme'] },
      { name: 'Mercedes-Benz Tourismo', seats: '50+1 / 54+1', image: '/images/fleet/otobus.png', features: ['Wi-Fi Bağlantısı', 'Geniş Bagaj Hacmi', 'Akıllı Klima Sistemi'] },
      { name: 'Neoplan Tourliner', seats: '50+1', image: '/images/fleet/otobus.png', features: ['Panoramik Camlar', 'Premium Koltuk Kumaşları', 'Çift Bölge İklimlendirme'] },
      { name: 'Temsa Safir Plus', seats: '41+1 / 46+1', image: '/images/fleet/otobus.png', features: ['Geniş İç Hacim', 'Konforlu Koltuklar', 'Kesintisiz Wi-Fi'] },
      { name: 'MAN Fortuna', seats: '46+1 / 54+1', image: '/images/fleet/otobus.png', features: ['Yüksek Güvenlik', 'Geniş Diz Mesafesi', 'Sessiz Kabin'] }
    ]
  },
  {
    id: 'midibus',
    title: 'Midibüs Filomuz',
    desc: 'Orta ölçekli gruplar için tasarlanmış, kıvrak, konforlu ve ekonomik seyahat çözümleri.',
    vehicles: [
      { name: 'Otokar Sultan Mega', seats: '31+1', image: '/images/fleet/midibus.png', features: ['Geniş İç Hacim', 'Yatar Koltuklar', 'Güçlü Klima'] },
      { name: 'Isuzu Turkuaz', seats: '31+1', image: '/images/fleet/midibus.png', features: ['Ergonomik Koltuklar', 'Bağımsız Süspansiyon', 'Dijital Klima'] },
      { name: 'Temsa Prestij SX', seats: '29+1', image: '/images/fleet/midibus.png', features: ['Sessiz Motor', 'Yüksek Bagaj Hacmi', 'USB Şarj Çıkışları'] },
      { name: 'Otokar Sultan Maxi', seats: '31+1', image: '/images/fleet/midibus.png', features: ['Yüksek Tavan', 'Gelişmiş Fren', 'Geniş Diz Mesafesi'] },
      { name: 'Isuzu Novo Lux', seats: '27+1', image: '/images/fleet/midibus.png', features: ['Premium İç Dizayn', 'Hava Süspansiyon', 'Buzdolabı'] }
    ]
  },
  {
    id: 'minibus',
    title: 'Minibüs Filomuz',
    desc: 'Küçük gruplar, personel taşımacılığı ve butik turlar için ideal, seri ve donanımlı minibüslerimiz.',
    vehicles: [
      { name: 'Mercedes-Benz Sprinter', seats: '16+1 / 19+1', image: '/images/fleet/minibus.png', features: ['Gelişmiş Güvenlik', 'Yüksek Tavan Ferahlığı', 'Bağımsız Arka Klima'] },
      { name: 'Volkswagen Crafter', seats: '16+1 / 19+1', image: '/images/fleet/minibus.png', features: ['ErgoComfort Koltuklar', 'Sessiz Kabin', 'Çift Bölge İklimlendirme'] },
      { name: 'Ford Transit', seats: '16+1', image: '/images/fleet/minibus.png', features: ['Dinamik Sürüş', 'Geniş İç Hacim', 'Güçlü Klima'] },
      { name: 'Citroen Jumper', seats: '16+1', image: '/images/fleet/minibus.png', features: ['Kompakt Tasarım', 'Ekonomik Seyahat', 'Konforlu Süspansiyon'] },
      { name: 'Peugeot Boxer', seats: '16+1', image: '/images/fleet/minibus.png', features: ['Ergonomik Yolcu Alanı', 'Güvenli Sürüş Paketi', 'Modern İç Dizayn'] }
    ]
  },
  {
    id: 'vip',
    title: 'VIP Araç Filomuz',
    desc: 'Protokol, yönetici transferleri ve özel konuklarınız için ultra lüks tasarımlı VIP araçlarımız.',
    vehicles: [
      { name: 'Mercedes-Benz Vito VIP', seats: '6+1', image: '/images/fleet/vip.png', features: ['Maybach Dizayn Tavan', 'Hakiki Deri Koltuklar', 'Araç İçi Eğlence Sistemi'] },
      { name: 'Mercedes-Benz Sprinter VIP', seats: '9+1 / 12+1', image: '/images/fleet/vip.png', features: ['Ultra Geniş Diz Mesafesi', 'Business Class Koltuklar', 'Çalışma Masası'] },
      { name: 'Volkswagen Caravelle VIP', seats: '8+1', image: '/images/fleet/vip.png', features: ['Ergonomik VIP Koltuklar', 'Bağımsız Dijital Klima', 'Gizli Çalışma Masaları'] },
      { name: 'Mercedes-Benz Maybach', seats: '3+1', image: '/images/fleet/vip.png', features: ['Ultra Lüks Deri Döşeme', 'Isıtmalı/Soğutmalı Koltuklar', 'Burmester Ses Sistemi'] }
    ]
  },
  {
    id: 'otomobil',
    title: 'Otomobil Filomuz',
    desc: 'Bireysel kiralama, şoförlü tahsis ve protokol taşımacılığına uygun prestijli otomobillerimiz.',
    vehicles: [
      { name: 'Mercedes-Benz E-Class', seats: '4+1', image: '/images/fleet/otomobil.png', features: ['Deri Döşeme', 'Isıtmalı Koltuklar', 'Sessiz Kabin'] },
      { name: 'Volkswagen Passat', seats: '4+1', image: '/images/fleet/otomobil.png', features: ['ErgoComfort Koltuklar', 'Geniş Bagaj (586L)', 'Üç Bölgeli Dijital Klima'] },
      { name: 'Audi A6', seats: '4+1', image: '/images/fleet/otomobil.png', features: ['Valcona Deri Döşeme', 'Matrix LED Farlar', 'Sanal Kokpit'] },
      { name: 'BMW 5 Serisi', seats: '4+1', image: '/images/fleet/otomobil.png', features: ['Dakota Deri Koltuklar', 'Gelişmiş Sürüş Asistanı', 'Harman Kardon Müzik'] },
      { name: 'Skoda Superb', seats: '4+1', image: '/images/fleet/otomobil.png', features: ['Devasa Arka Diz Mesafesi', '625L Bagaj Kapasitesi', 'Sessiz Sürüş'] }
    ]
  }
];

export default function FilomuzPage({ onBack, onQuoteClick }) {
  return (
    <motion.div 
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      exit={{ opacity: 0 }}
      style={{ background: '#f8fafc', minHeight: '100vh', paddingBottom: '100px' }}
    >
      {/* Premium Hero Section */}
      <div style={{
        background: 'linear-gradient(135deg, #0f172a 0%, #1e293b 100%)',
        padding: '160px 5% 100px',
        color: 'white',
        textAlign: 'center',
        position: 'relative',
        overflow: 'hidden'
      }}>
        {/* Background Gradients */}
        <div style={{ position: 'absolute', top: '-50%', left: '-10%', width: '60%', height: '200%', background: 'radial-gradient(circle, rgba(16,84,156,0.3) 0%, rgba(0,0,0,0) 70%)', transform: 'rotate(30deg)' }} />
        <div style={{ position: 'absolute', bottom: '-50%', right: '-10%', width: '60%', height: '200%', background: 'radial-gradient(circle, rgba(226,27,27,0.2) 0%, rgba(0,0,0,0) 70%)', transform: 'rotate(-30deg)' }} />
        
        <div style={{ position: 'relative', zIndex: 10, maxWidth: '800px', margin: '0 auto' }}>
          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.2 }}
            style={{ display: 'inline-flex', alignItems: 'center', gap: '10px', background: 'rgba(255,255,255,0.1)', padding: '8px 20px', borderRadius: '50px', marginBottom: '20px', backdropFilter: 'blur(10px)' }}
          >
            <Star size={16} color="var(--color-accent-secondary)" />
            <span style={{ fontSize: '0.9rem', letterSpacing: '2px', textTransform: 'uppercase', fontWeight: 600 }}>Tüm Araçlar Tek Çatı Altında</span>
          </motion.div>
          <motion.h1 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.3 }}
            style={{ fontSize: 'clamp(2.5rem, 5vw, 4rem)', fontWeight: 800, marginBottom: '20px', fontFamily: 'var(--font-heading)' }}
          >
            Geniş ve Lüks <span style={{ color: 'var(--color-accent-secondary)' }}>Filomuz</span>
          </motion.h1>
          <motion.p 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.4 }}
            style={{ fontSize: '1.2rem', color: 'rgba(255,255,255,0.7)', lineHeight: 1.6 }}
          >
            İhtiyacınız olan her kapasiteye ve standarta uygun, son model ve periyodik bakımları eksiksiz yapılan dev araç filomuzu keşfedin.
          </motion.p>
        </div>
      </div>

      {/* Main Categories Container */}
      <div style={{ maxWidth: '1400px', margin: '0 auto', padding: '0 5%', marginTop: '-40px', position: 'relative', zIndex: 20 }}>
        
        {fleetData.map((category, index) => (
          <motion.div 
            key={category.id}
            initial={{ opacity: 0, y: 50 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: "-100px" }}
            transition={{ duration: 0.6 }}
            style={{ 
              background: '#fff', 
              borderRadius: '24px', 
              boxShadow: '0 20px 40px rgba(0,0,0,0.06)',
              padding: 'clamp(30px, 5vw, 50px)',
              marginBottom: '40px',
              borderTop: `4px solid ${index % 2 === 0 ? '#10549c' : '#e21b1b'}`
            }}
          >
            {/* Category Header */}
            <div style={{ display: 'flex', flexWrap: 'wrap', justifyContent: 'space-between', alignItems: 'flex-end', marginBottom: '40px', borderBottom: '1px solid #f1f5f9', paddingBottom: '20px' }}>
              <div>
                <h2 style={{ fontSize: '2.2rem', fontWeight: 800, color: '#0f172a', marginBottom: '10px', fontFamily: 'var(--font-heading)' }}>
                  {category.title}
                </h2>
                <p style={{ fontSize: '1.1rem', color: '#64748b', maxWidth: '600px', lineHeight: 1.6 }}>
                  {category.desc}
                </p>
              </div>
              <motion.button 
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
                onClick={onQuoteClick}
                style={{
                  background: 'linear-gradient(135deg, #10549c 0%, #1a73e8 100%)',
                  color: 'white',
                  border: 'none',
                  padding: '12px 24px',
                  borderRadius: '50px',
                  fontWeight: 600,
                  cursor: 'pointer',
                  display: 'flex',
                  alignItems: 'center',
                  gap: '8px',
                  boxShadow: '0 10px 20px rgba(16,84,156,0.2)',
                  marginTop: '20px'
                }}
              >
                Hemen Teklif Al <ArrowRight size={18} />
              </motion.button>
            </div>

            {/* Vehicles Grid */}
            <div style={{ 
              display: 'grid', 
              gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', 
              gap: '30px' 
            }}>
              {category.vehicles.map((vehicle, vIdx) => (
                <motion.div 
                  key={vIdx}
                  whileHover={{ y: -10, boxShadow: '0 20px 40px rgba(0,0,0,0.1)' }}
                  style={{
                    background: '#f8fafc',
                    borderRadius: '16px',
                    overflow: 'hidden',
                    border: '1px solid #e2e8f0',
                    transition: 'all 0.3s ease'
                  }}
                >
                  {/* Vehicle Image */}
                  <div style={{ 
                    height: '180px', 
                    background: '#e2e8f0', 
                    position: 'relative',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    overflow: 'hidden'
                  }}>
                    <img 
                      src={vehicle.image} 
                      alt={vehicle.name}
                      style={{ width: '80%', height: 'auto', objectFit: 'contain', filter: 'drop-shadow(0 10px 15px rgba(0,0,0,0.2))' }}
                      onError={(e) => {
                        e.target.style.display = 'none';
                        e.target.nextSibling.style.display = 'flex';
                      }}
                    />
                    <div style={{ display: 'none', flexDirection: 'column', alignItems: 'center', color: '#94a3b8' }}>
                      <Bus size={48} strokeWidth={1} />
                      <span style={{ fontSize: '0.9rem', marginTop: '10px' }}>Görsel Hazırlanıyor</span>
                    </div>
                  </div>

                  {/* Vehicle Details */}
                  <div style={{ padding: '24px' }}>
                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: '15px' }}>
                      <h3 style={{ fontSize: '1.25rem', fontWeight: 700, color: '#0f172a', lineHeight: 1.3 }}>{vehicle.name}</h3>
                      <div style={{ background: 'rgba(16,84,156,0.1)', color: '#10549c', padding: '4px 8px', borderRadius: '8px', fontSize: '0.8rem', fontWeight: 700, display: 'flex', alignItems: 'center', gap: '4px' }}>
                        <Users size={14} /> {vehicle.seats}
                      </div>
                    </div>

                    <ul style={{ listStyle: 'none', padding: 0, margin: 0, display: 'flex', flexDirection: 'column', gap: '8px' }}>
                      {vehicle.features.map((feature, fIdx) => (
                        <li key={fIdx} style={{ display: 'flex', alignItems: 'center', gap: '8px', color: '#64748b', fontSize: '0.95rem' }}>
                          <div style={{ width: '6px', height: '6px', borderRadius: '50%', background: 'var(--color-accent-secondary)' }} />
                          {feature}
                        </li>
                      ))}
                    </ul>
                  </div>
                </motion.div>
              ))}
            </div>
            
          </motion.div>
        ))}
      </div>
    </motion.div>
  );
}
