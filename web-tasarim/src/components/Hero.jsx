import React, { useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { ArrowRight, ChevronDown, CheckCircle2, ChevronLeft, ChevronRight } from 'lucide-react';

const videoList = [
  '/videos/personel_video.mp4',
  '/videos/ogrenci_video.mp4',
  '/videos/turizm_video.mp4',
  '/videos/vip_video.mp4',
  '/videos/kiralama_video.mp4'
];

const heroContents = [
  {
    subtitle: "İŞİNİZE DEĞER KATIYORUZ",
    title: "Kurumsal Personel Taşımacılığı",
    description: "Çalışanlarınızın her güne zinde, güvenli ve motive başlaması kurumunuzun en büyük gücüdür. Modern filomuz, ileri teknoloji rota planlama sistemlerimiz ve deneyimli kadromuzla personel taşımacılığını bir ayrıcalığa dönüştürüyoruz.",
    features: ["Akıllı Rota Optimizasyonu", "Dakik ve Güvenli", "Konforlu Yeni Filo"]
  },
  {
    subtitle: "GELECEĞİMİZ GÜVENDE",
    title: "Öğrenci Servis Taşımacılığı",
    description: "Çocuklarınızın evden okula, okuldan eve olan yolculuklarında maksimum güvenlik standartlarını uyguluyoruz. Tam donanımlı araçlarımız, Veli Bilgilendirme Sistemi ve eğitimli rehber personelimizle gözünüz asla arkada kalmaz.",
    features: ["Canlı Araç Takibi", "Eğitimli Rehber", "Mobil Uygulama ile Takip"]
  },
  {
    subtitle: "YOLCULUK DEĞİL, DENEYİM",
    title: "Turizm ve Gezi Taşımacılığı",
    description: "Türkiye'nin her köşesinde misafirlerinize VIP standartlarında seyahat deneyimi sunuyoruz. Geniş iç hacim, lüks donanım ve üst düzey hizmet anlayışıyla yolculuğun her anını unutulmaz kılıyoruz.",
    features: ["Çok Dilli Sürücüler", "Premium Donanım", "Özel Karşılama"]
  },
  {
    subtitle: "SADECE SİZE ÖZEL",
    title: "VIP Transfer Hizmetleri",
    description: "Havalimanı, protokol, özel etkinlik veya iş seyahatlerinizde beklentilerin ötesinde bir hizmet. Özel tasarımlı lüks araçlarımız ve profesyonel asistan şoförlerimizle her anınızı ayrıcalıklı kılın.",
    features: ["7/24 Özel Asistanlık", "Üst Düzey Gizlilik", "Ultra Lüks Araçlar"]
  },
  {
    subtitle: "KESİNTİSİZ ÖZGÜRLÜK",
    title: "Bireysel ve Kurumsal Araç Kiralama",
    description: "İhtiyaçlarınıza tam uyum sağlayan geniş, bakımlı ve yeni nesil filomuzla yollara hükmedin. Esnek sözleşme şartları, tam kapsamlı kasko ve 7/24 yol yardım desteğiyle sürüş keyfinizi garanti altına alıyoruz.",
    features: ["Esnek Kiralama", "7/24 Yol Yardım", "Geniş Araç Seçeneği"]
  }
];

export default function Hero({ activeIndex = 0, isIntroPlaying = false, onVideoEnd, onCTAClick, onNext, onPrev }) {
  useEffect(() => {
    let timer;
    if (activeIndex === 1) {
      // Cut the student transport video at 7 seconds
      timer = setTimeout(() => {
        onVideoEnd && onVideoEnd();
      }, 7000);
    }
    return () => clearTimeout(timer);
  }, [activeIndex, onVideoEnd]);

  return (
    <section 
      className="hero-section"
      style={{ 
        position: 'relative',
        height: '85vh', 
        minHeight: '650px',
        width: '98%',
        margin: '0 auto',
        marginTop: '10px',
        display: 'flex', 
        alignItems: 'center', 
        justifyContent: 'center',
        overflow: 'hidden',
        borderRadius: '45px',
        boxShadow: '0 20px 40px rgba(0,0,0,0.1)'
      }}
    >
      <style>{`
        @media (max-width: 768px) {
          .hero-section { 
            height: auto !important; 
            min-height: 0 !important; 
            aspect-ratio: 16 / 9 !important; 
          }
          .hero-card-container { display: none !important; }
        }
      `}</style>
      {/* Background Video with Parallax-like scale effect */}
      <motion.div 
        initial={{ scale: 1.1 }}
        animate={{ scale: 1 }}
        transition={{ duration: 1.5, ease: "easeOut" }}
        style={{
          position: 'absolute',
          top: 0,
          left: 0,
          width: '100%',
          height: '100%',
          zIndex: 1,
          overflow: 'hidden',
          borderRadius: '45px',
          backgroundColor: '#0a192f'
        }}
      >
        {/* AnimatePresence for smooth fade transitions between videos if needed, but keying the video itself is robust */}
        <AnimatePresence mode="wait">
          <motion.video
            className="hero-video"
            key={isIntroPlaying ? 'intro' : videoList[activeIndex]}
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            transition={{ duration: 0.8 }}
            autoPlay
            muted
            playsInline
            onEnded={onVideoEnd}

            style={{
              width: '100%',
              height: '100%',
              objectFit: 'cover',
              objectPosition: 'center'
            }}
          >
            <source src={isIntroPlaying ? '/videos/intro_video.mp4' : videoList[activeIndex]} type="video/mp4" />
          </motion.video>
        </AnimatePresence>
      </motion.div>

      {/* Navigation Arrows */}
      {!isIntroPlaying && (
        <>
          <div style={{ position: 'absolute', top: '50%', left: '3%', transform: 'translateY(-50%)', zIndex: 20 }}>
            <motion.button 
              onClick={(e) => { e.stopPropagation(); onPrev && onPrev(); }}
              whileHover={{ scale: 1.1, background: 'rgba(255,255,255,0.2)' }}
              whileTap={{ scale: 0.9 }}
              style={{ width: '50px', height: '50px', borderRadius: '50%', background: 'rgba(0,0,0,0.3)', border: '1px solid rgba(255,255,255,0.2)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#FFF', cursor: 'pointer', backdropFilter: 'blur(5px)' }}
            >
              <ChevronLeft size={30} />
            </motion.button>
          </div>
          <div style={{ position: 'absolute', top: '50%', right: '3%', transform: 'translateY(-50%)', zIndex: 20 }}>
            <motion.button 
              onClick={(e) => { e.stopPropagation(); onNext && onNext(); }}
              whileHover={{ scale: 1.1, background: 'rgba(255,255,255,0.2)' }}
              whileTap={{ scale: 0.9 }}
              style={{ width: '50px', height: '50px', borderRadius: '50%', background: 'rgba(0,0,0,0.3)', border: '1px solid rgba(255,255,255,0.2)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#FFF', cursor: 'pointer', backdropFilter: 'blur(5px)' }}
            >
              <ChevronRight size={30} />
            </motion.button>
          </div>
        </>
      )}

      {/* Subtle Gradient Overlay only at the bottom for the scroll down arrow and 3D depth */}
      <div 
        style={{
          position: 'absolute',
          top: 0,
          left: 0,
          width: '100%',
          height: '100%',
          background: 'linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 70%, rgba(0,0,0,0.5) 100%)',
          zIndex: 2,
          pointerEvents: 'none'
        }}
      />

      {/* Dynamic Content Overlay (Premium Glassmorphism on the right bottom) */}
      {!isIntroPlaying && (
        <div 
        className="hero-card-container"
        style={{ 
          position: 'absolute', 
          zIndex: 10, 
          bottom: '50px', // Positioned at bottom right elegantly
          right: '50px', 
          maxWidth: 'min(560px, 90vw)',
          width: '90%',
          pointerEvents: 'none' // Allows clicking through
        }}
      >
        <AnimatePresence mode="wait">
          <motion.div
            key={activeIndex}
            className="hero-glass-card"
            initial={{ opacity: 0, x: 60, filter: 'blur(15px)' }}
            animate={{ opacity: 1, x: 0, filter: 'blur(0px)' }}
            exit={{ opacity: 0, x: -60, filter: 'blur(15px)' }}
            transition={{ duration: 0.9, ease: [0.16, 1, 0.3, 1] }} // Super smooth cinematic ease
            style={{
              background: 'linear-gradient(135deg, rgba(15, 23, 42, 0.75) 0%, rgba(15, 23, 42, 0.4) 100%)',
              backdropFilter: 'blur(20px)',
              WebkitBackdropFilter: 'blur(20px)',
              padding: 'clamp(25px, 5vw, 45px) clamp(20px, 5vw, 50px)',
              borderRadius: '30px',
              border: '1px solid rgba(255,255,255,0.1)',
              borderTop: '1px solid rgba(255,255,255,0.25)',
              borderLeft: '1px solid rgba(255,255,255,0.25)',
              boxShadow: '0 30px 60px rgba(0,0,0,0.5), inset 0 0 20px rgba(255,255,255,0.05)',
              color: '#FFFFFF',
              pointerEvents: 'auto' // Make internal elements clickable
            }}
          >
            {/* Subtitle */}
            <motion.div 
              className="hero-subtitle"
              initial={{ opacity: 0, y: 10 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.3, duration: 0.6 }}
              style={{
                color: 'var(--color-accent-secondary)', // Premium Ruby Red
                fontSize: '0.85rem',
                fontWeight: 700,
                letterSpacing: '3px',
                textTransform: 'uppercase',
                marginBottom: '12px',
                display: 'flex',
                alignItems: 'center',
                gap: '12px'
              }}
            >
              <div style={{ width: '30px', height: '2px', background: 'var(--color-accent-secondary)' }}></div>
              {heroContents[activeIndex].subtitle}
            </motion.div>

            {/* Title */}
            <motion.h2 
              className="hero-title"
              initial={{ opacity: 0, y: 10 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.4, duration: 0.6 }}
              style={{ 
                fontSize: 'clamp(2rem, 4vw, 2.8rem)', 
                fontWeight: 800, 
                marginBottom: '24px', 
                lineHeight: 1.1, 
                fontFamily: 'var(--font-heading)',
                background: 'linear-gradient(to right, #FFFFFF, #E0E0E0)',
                WebkitBackgroundClip: 'text',
                WebkitTextFillColor: 'transparent',
                textShadow: '0 10px 30px rgba(0,0,0,0.5)'
              }}
            >
              {heroContents[activeIndex].title}
            </motion.h2>

            {/* Description */}
            <motion.p 
              className="hero-description"
              initial={{ opacity: 0, y: 10 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.5, duration: 0.6 }}
              style={{ 
                fontSize: '1.1rem', 
                lineHeight: 1.7, 
                color: 'rgba(255,255,255,0.85)', 
                marginBottom: '32px',
                textShadow: '0 2px 4px rgba(0,0,0,0.5)'
              }}
            >
              {heroContents[activeIndex].description}
            </motion.p>
            
            {/* Features (Pills) */}
            {heroContents[activeIndex].features && heroContents[activeIndex].features.length > 0 && (
              <motion.div 
                className="hero-features"
                initial={{ opacity: 0, y: 10 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 0.6, duration: 0.6 }}
                style={{ display: 'flex', gap: '12px', flexWrap: 'wrap', marginBottom: '36px' }}
              >
                {heroContents[activeIndex].features.map((feature, i) => (
                  <div 
                    key={i} 
                    className="hero-feature-pill"
                    style={{ 
                      display: 'flex', 
                      alignItems: 'center', 
                      gap: '8px', 
                      background: 'rgba(255,255,255,0.08)', 
                      padding: '10px 18px', 
                      borderRadius: '12px', 
                      fontSize: '0.85rem', 
                      fontWeight: 600,
                      border: '1px solid rgba(255,255,255,0.1)',
                      boxShadow: '0 4px 10px rgba(0,0,0,0.2)',
                      backdropFilter: 'blur(10px)'
                    }}
                  >
                    <CheckCircle2 size={16} color="var(--color-accent-secondary)" strokeWidth={3} />
                    <span style={{ color: '#F8F9FA' }}>{feature}</span>
                  </div>
                ))}
              </motion.div>
            )}

            {/* CTA Button */}
            <motion.a 
              className="hero-cta"
              onClick={() => onCTAClick && onCTAClick(activeIndex)}
              initial={{ opacity: 0, y: 10 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.7, duration: 0.6 }}
              whileHover={{ scale: 1.05, background: 'linear-gradient(135deg, #ff4d5a, #c21927)' }}
              whileTap={{ scale: 0.95 }}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '12px',
                background: 'linear-gradient(135deg, #e63946 0%, #a71d2a 100%)',
                padding: '16px 32px',
                borderRadius: '50px',
                color: '#FFF',
                fontWeight: 700,
                fontSize: '1rem',
                textDecoration: 'none',
                boxShadow: '0 10px 25px rgba(230, 57, 70, 0.4)',
                cursor: 'pointer'
              }}
            >
              Hizmeti İncele
              <ArrowRight size={20} strokeWidth={2.5} />
            </motion.a>
          </motion.div>
        </AnimatePresence>
      </div>
      )}
    </section>
  );
}
