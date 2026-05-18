import React, { useState, useEffect, useContext } from 'react';
import { View, Text, StyleSheet, FlatList, TouchableOpacity, ActivityIndicator, Alert, RefreshControl, Dimensions, Linking, Platform } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { MaterialCommunityIcons as Icon } from '@expo/vector-icons';
import api from '../api/axios';
import { AuthContext } from '../context/AuthContext';
import { EmptyState } from '../components';
import { LinearGradient } from 'expo-linear-gradient';

export default function TendersScreen({ navigation }) {
    const { hasPermission } = useContext(AuthContext);
    const [tenders, setTenders] = useState([]);
    const [stats, setStats] = useState({ total: 0, won: 0, lost: 0, evaluating: 0 });
    const [loading, setLoading] = useState(true);
    const [refreshing, setRefreshing] = useState(false);

    const fetchTenders = async (isRefreshing = false) => {
        if (!isRefreshing) setLoading(true);
        try {
            const r = await api.get('/v1/tenders');
            if (r.data.success) {
                setTenders(r.data.data);
                if (r.data.stats) setStats(r.data.stats);
            }
        } catch (e) {
            console.error(e);
        } finally {
            setLoading(false);
            setRefreshing(false);
        }
    };

    useEffect(() => {
        const unsubscribe = navigation.addListener('focus', () => {
            fetchTenders();
        });
        return unsubscribe;
    }, [navigation]);

    const confirmDelete = (id) => {
        if (!hasPermission('tenders.delete')) {
            Alert.alert('Yetki Yok', 'Silme yetkiniz yok.');
            return;
        }
        Alert.alert('Silinecek', 'Bu ihale kaydını silmek istediğinize emin misiniz?', [
            { text: 'İptal', style: 'cancel' },
            { text: 'Sil', style: 'destructive', onPress: async () => {
                try {
                    await api.delete(`/v1/tenders/${id}`);
                    fetchTenders();
                } catch(e) {}
            }}
        ]);
    };

    const getStatusStyle = (status) => {
        switch(status) {
            case 'Kazanıldı': return { bg: '#ECFDF5', text: '#10B981', dot: '#10B981' };
            case 'Kaybedildi': return { bg: '#FEF2F2', text: '#EF4444', dot: '#EF4444' };
            case 'Değerlendirmede': return { bg: '#FFFBEB', text: '#F59E0B', dot: '#F59E0B' };
            default: return { bg: '#F1F5F9', text: '#64748B', dot: '#64748B' };
        }
    };

    const formatMoney = (val) => {
        if (!val) return '-';
        return Number(val).toLocaleString('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ₺';
    };

    const renderItem = ({ item }) => {
        const statusStyle = getStatusStyle(item.status);

        return (
            <View style={st.card}>
                <View style={st.cardHeader}>
                    <View style={[st.statusBadge, { backgroundColor: statusStyle.bg }]}>
                        <View style={[st.statusDot, { backgroundColor: statusStyle.dot }]} />
                        <Text style={[st.statusText, { color: statusStyle.text }]}>{item.status?.toUpperCase()}</Text>
                    </View>
                    <Text style={st.dateText}>{item.tender_date ? new Date(item.tender_date).toLocaleDateString('tr-TR') : '-'}</Text>
                </View>

                <Text style={st.institutionName}>{item.institution_name}</Text>
                {item.tender_registration_number ? <Text style={st.iknText}>İKN: {item.tender_registration_number}</Text> : null}

                <View style={st.infoGrid}>
                    <View style={st.infoBox}>
                        <Text style={st.infoLabel}>ARAÇ İHTİYACI</Text>
                        <Text style={st.infoValue} numberOfLines={2}>{item.vehicle_details || 'Belirtilmedi'}</Text>
                    </View>
                    <View style={st.infoBox}>
                        <Text style={st.infoLabel}>İŞİN SÜRESİ</Text>
                        <Text style={st.infoValue}>{item.duration_days ? `${item.duration_days} Gün` : '-'}</Text>
                    </View>
                </View>

                <View style={st.financeBox}>
                    <View style={st.financeRow}>
                        <Text style={st.financeLabel}>Bizim Teklifimiz</Text>
                        <Text style={[st.financeValue, { color: '#3B82F6' }]}>{formatMoney(item.our_bid)}</Text>
                    </View>
                    <View style={st.divider} />
                    <View style={st.financeRow}>
                        <Text style={st.financeLabel}>Kazanan Firma / Teklif</Text>
                        <View style={{ alignItems: 'flex-end' }}>
                            <Text style={[st.financeValue, { color: '#F59E0B' }]}>{formatMoney(item.winning_amount)}</Text>
                            <Text style={st.winningCompany}>{item.winning_company || 'Bilinmiyor'}</Text>
                        </View>
                    </View>
                </View>

                <View style={st.actionRow}>
                    {item.file_url ? (
                        <TouchableOpacity style={[st.actionBtn, { backgroundColor: '#EFF6FF' }]} onPress={() => Linking.openURL(item.file_url)}>
                            <Icon name="file-pdf-box" size={18} color="#3B82F6" />
                            <Text style={[st.actionBtnText, { color: '#3B82F6' }]}>PDF</Text>
                        </TouchableOpacity>
                    ) : (
                        <View style={st.actionBtn} />
                    )}

                    <View style={{ flexDirection: 'row', gap: 8 }}>
                        {hasPermission('tenders.edit') && (
                            <TouchableOpacity style={[st.actionBtn, { backgroundColor: '#F8FAFC' }]} onPress={() => navigation.navigate('TenderForm', { tenderId: item.id })}>
                                <Icon name="pencil" size={18} color="#64748B" />
                            </TouchableOpacity>
                        )}
                        {hasPermission('tenders.delete') && (
                            <TouchableOpacity style={[st.actionBtn, { backgroundColor: '#FEF2F2' }]} onPress={() => confirmDelete(item.id)}>
                                <Icon name="trash-can-outline" size={18} color="#EF4444" />
                            </TouchableOpacity>
                        )}
                    </View>
                </View>
            </View>
        );
    };

    return (
        <View style={st.container}>
            <LinearGradient colors={['#F8FAFC', '#F1F5F9']} style={StyleSheet.absoluteFillObject} />
            <SafeAreaView style={{ flex: 1 }} edges={['top']}>
                
                <View style={st.header}>
                    <TouchableOpacity onPress={() => navigation.goBack()} style={st.backBtn}>
                        <Icon name="chevron-left" size={28} color="#0F172A" />
                    </TouchableOpacity>
                    <View style={st.headerCenter}>
                        <Text style={st.headerTitle}>İhaleler</Text>
                        <Text style={st.headerSubtitle}>Geçmiş İhale Arşivi</Text>
                    </View>
                    {hasPermission('tenders.create') ? (
                        <TouchableOpacity style={st.addHeaderBtn} onPress={() => navigation.navigate('TenderForm')}>
                            <Icon name="plus" size={24} color="#fff" />
                        </TouchableOpacity>
                    ) : <View style={{ width: 44 }} />}
                </View>

                <View style={st.statsContainer}>
                    <View style={st.statBox}>
                        <Text style={st.statValue}>{stats.total}</Text>
                        <Text style={st.statLabel}>KAYITLI İHALE</Text>
                    </View>
                    <View style={st.statDivider} />
                    <View style={st.statBox}>
                        <Text style={[st.statValue, { color: '#10B981' }]}>{stats.won}</Text>
                        <Text style={st.statLabel}>KAZANILAN</Text>
                    </View>
                    <View style={st.statDivider} />
                    <View style={st.statBox}>
                        <Text style={[st.statValue, { color: '#EF4444' }]}>{stats.lost}</Text>
                        <Text style={st.statLabel}>KAYBEDİLEN</Text>
                    </View>
                </View>

                {loading ? (
                    <View style={st.loader}><ActivityIndicator size="large" color="#3B82F6" /></View>
                ) : (
                    <FlatList
                        data={tenders}
                        renderItem={renderItem}
                        keyExtractor={item => item.id.toString()}
                        contentContainerStyle={st.listContent}
                        showsVerticalScrollIndicator={false}
                        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={() => fetchTenders(true)} tintColor="#3B82F6" />}
                        ListEmptyComponent={<EmptyState title="İhale Bulunamadı" message="Henüz sisteme eklenmiş bir ihale kaydı bulunmuyor." icon="briefcase-outline" />}
                    />
                )}
            </SafeAreaView>
        </View>
    );
}

const st = StyleSheet.create({
    container: { flex: 1 },
    loader: { flex: 1, justifyContent: 'center', alignItems: 'center' },
    header: { flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', paddingHorizontal: 16, paddingBottom: 16, paddingTop: 8 },
    backBtn: { width: 44, height: 44, borderRadius: 22, backgroundColor: '#fff', alignItems: 'center', justifyContent: 'center', shadowColor: '#000', shadowOffset: { width: 0, height: 2 }, shadowOpacity: 0.05, shadowRadius: 4, elevation: 2 },
    headerCenter: { flex: 1, alignItems: 'center' },
    headerTitle: { fontSize: 20, fontWeight: '900', color: '#0F172A' },
    headerSubtitle: { fontSize: 13, fontWeight: '600', color: '#3B82F6', marginTop: 2 },
    addHeaderBtn: { width: 44, height: 44, borderRadius: 22, backgroundColor: '#3B82F6', alignItems: 'center', justifyContent: 'center', shadowColor: '#3B82F6', shadowOffset: { width: 0, height: 4 }, shadowOpacity: 0.4, shadowRadius: 6, elevation: 4 },
    
    statsContainer: { flexDirection: 'row', backgroundColor: '#fff', marginHorizontal: 16, borderRadius: 20, padding: 16, shadowColor: '#000', shadowOffset: { width: 0, height: 4 }, shadowOpacity: 0.05, shadowRadius: 10, elevation: 2, marginBottom: 8 },
    statBox: { flex: 1, alignItems: 'center' },
    statValue: { fontSize: 20, fontWeight: '900', color: '#0F172A' },
    statLabel: { fontSize: 10, fontWeight: '800', color: '#94A3B8', marginTop: 4, letterSpacing: 0.5 },
    statDivider: { width: 1, height: '80%', backgroundColor: '#F1F5F9', alignSelf: 'center' },

    listContent: { padding: 16, paddingBottom: 120 },
    card: { backgroundColor: '#fff', borderRadius: 24, padding: 20, marginBottom: 16, shadowColor: '#000', shadowOffset: { width: 0, height: 8 }, shadowOpacity: 0.05, shadowRadius: 15, elevation: 3, borderWidth: 1, borderColor: '#F1F5F9' },
    
    cardHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 12 },
    statusBadge: { flexDirection: 'row', alignItems: 'center', paddingHorizontal: 10, paddingVertical: 5, borderRadius: 10, gap: 6 },
    statusDot: { width: 6, height: 6, borderRadius: 3 },
    statusText: { fontSize: 10, fontWeight: '800', letterSpacing: 0.5 },
    dateText: { fontSize: 12, fontWeight: '700', color: '#94A3B8' },

    institutionName: { fontSize: 17, fontWeight: '900', color: '#1E293B', marginBottom: 2 },
    iknText: { fontSize: 12, fontWeight: '600', color: '#64748B', marginBottom: 12 },

    infoGrid: { flexDirection: 'row', gap: 12, marginBottom: 16 },
    infoBox: { flex: 1, backgroundColor: '#F8FAFC', padding: 10, borderRadius: 12 },
    infoLabel: { fontSize: 10, fontWeight: '800', color: '#94A3B8', marginBottom: 4 },
    infoValue: { fontSize: 13, fontWeight: '700', color: '#334155' },

    financeBox: { backgroundColor: '#F8FAFC', borderRadius: 16, padding: 12, marginBottom: 16, borderWidth: 1, borderColor: '#F1F5F9' },
    financeRow: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
    financeLabel: { fontSize: 12, fontWeight: '700', color: '#64748B' },
    financeValue: { fontSize: 15, fontWeight: '900' },
    divider: { height: 1, backgroundColor: '#E2E8F0', marginVertical: 8 },
    winningCompany: { fontSize: 11, fontWeight: '600', color: '#94A3B8', marginTop: 2 },

    actionRow: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', paddingTop: 12, borderTopWidth: 1, borderTopColor: '#F1F5F9' },
    actionBtn: { flexDirection: 'row', alignItems: 'center', justifyContent: 'center', paddingHorizontal: 16, paddingVertical: 8, borderRadius: 10, gap: 6 },
    actionBtnText: { fontSize: 12, fontWeight: '800' }
});
