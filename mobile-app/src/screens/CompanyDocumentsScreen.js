import React, { useState, useEffect, useContext } from 'react';
import { View, Text, StyleSheet, FlatList, TouchableOpacity, ActivityIndicator, Alert, RefreshControl, Dimensions, Linking, Platform, Modal, ScrollView, Image } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { MaterialCommunityIcons as Icon } from '@expo/vector-icons';
import api from '../api/axios';
import { AuthContext } from '../context/AuthContext';
import { EmptyState, FormField } from '../components';
import * as DocumentPicker from 'expo-document-picker';
import * as FileSystem from 'expo-file-system/legacy';
import * as Sharing from 'expo-sharing';
import { LinearGradient } from 'expo-linear-gradient';

const { width: W } = Dimensions.get('window');

export default function CompanyDocumentsScreen({ navigation }) {
    const { hasPermission } = useContext(AuthContext);
    const [documents, setDocuments] = useState([]);
    const [loading, setLoading] = useState(true);
    const [refreshing, setRefreshing] = useState(false);

    // Bulk selection
    const [selectedIds, setSelectedIds] = useState([]);

    // Form
    const [modalVisible, setModalVisible] = useState(false);
    const [saving, setSaving] = useState(false);
    const [file, setFile] = useState(null);
    const [documentName, setDocumentName] = useState('');

    const fetchDocuments = async (isRefreshing = false) => {
        if (!isRefreshing) setLoading(true);
        try {
            const r = await api.get('/v1/company-documents');
            if (r.data.data) {
                setDocuments(r.data.data);
            } else if (r.data.current_page !== undefined) {
                // If it's paginated, we use r.data.data from the pagination object
                setDocuments(r.data.data);
            } else {
                // Array directly or under data
                setDocuments(r.data);
            }
            setSelectedIds([]);
        } catch (e) {
            console.error(e);
        } finally {
            setLoading(false);
            setRefreshing(false);
        }
    };

    useEffect(() => {
        fetchDocuments();
    }, []);

    const pickDocument = async () => {
        try {
            const result = await DocumentPicker.getDocumentAsync({
                type: ['application/pdf', 'image/*'],
                copyToCacheDirectory: true,
            });
            if (!result.canceled && result.assets && result.assets.length > 0) {
                setFile(result.assets[0]);
            }
        } catch (err) {
            console.error("Document pick error", err);
        }
    };

    const handleSave = async () => {
        if (!documentName || !file) {
            Alert.alert('Eksik Bilgi', 'Belge adı ve dosya seçimi zorunludur.');
            return;
        }
        setSaving(true);
        try {
            const data = new FormData();
            data.append('document_name', documentName);
            
            const fName = file.name || file.uri.split('/').pop();
            const match = /\.(\w+)$/.exec(fName);
            const type = file.mimeType || (match ? (match[1] === 'pdf' ? 'application/pdf' : `image/${match[1]}`) : `application/octet-stream`);
            
            data.append('file', { uri: file.uri, name: fName, type });

            await api.post('/v1/company-documents', data, { headers: { 'Content-Type': 'multipart/form-data' }});
            
            setModalVisible(false);
            setDocumentName('');
            setFile(null);
            fetchDocuments();
            Alert.alert('Başarılı', 'Şirket evrağı yüklendi.');
        } catch (e) {
            Alert.alert('Hata', 'Kaydedilemedi: ' + (e.response?.data?.message || e.message));
        } finally {
            setSaving(false);
        }
    };

    const confirmDelete = (id) => {
        if (!hasPermission('company_documents.delete')) {
            Alert.alert('Yetki Yok', 'Silme yetkiniz yok.');
            return;
        }
        Alert.alert('Silinecek', 'Bu evrağı silmek istediğinize emin misiniz?', [
            { text: 'İptal', style: 'cancel' },
            { text: 'Sil', style: 'destructive', onPress: async () => {
                try {
                    await api.delete(`/v1/company-documents/${id}`);
                    fetchDocuments();
                } catch(e) {}
            }}
        ]);
    };

    const handleBulkDelete = () => {
        if (!hasPermission('company_documents.delete')) {
            Alert.alert('Yetki Yok', 'Silme yetkiniz yok.');
            return;
        }
        Alert.alert('Toplu Silme', `${selectedIds.length} adet evrağı silmek istediğinize emin misiniz?`, [
            { text: 'İptal', style: 'cancel' },
            { text: 'Sil', style: 'destructive', onPress: async () => {
                try {
                    await api.post('/v1/company-documents/bulk-delete', { ids: selectedIds });
                    fetchDocuments();
                } catch(e) {
                    Alert.alert('Hata', 'Silme işlemi başarısız oldu.');
                }
            }}
        ]);
    };

    const handleShare = async (doc) => {
        if (!doc.file_url) { Alert.alert('Hata', 'Paylaşılacak dosya bulunamadı.'); return; }
        try {
            const rawUrl = doc.file_url;
            const fileUrl = encodeURI(rawUrl);
            const ext = doc.file_url.split('.').pop() || 'pdf';
            const safeName = (doc.document_name || `belge_${doc.id}`).replace(/[^a-zA-Z0-9]/g, '_').toLowerCase();
            const localUri = `${FileSystem.cacheDirectory}${safeName}.${ext}`;
            
            const { uri, status } = await FileSystem.downloadAsync(fileUrl, localUri);
            
            if (status !== 200) {
                Alert.alert('Hata', `Dosya sunucudan indirilemedi. (Durum Kodu: ${status})`);
                return;
            }

            if (await Sharing.isAvailableAsync()) {
                await Sharing.shareAsync(uri, { 
                    mimeType: ext === 'pdf' ? 'application/pdf' : 'image/jpeg',
                    dialogTitle: 'Belgeyi Paylaş', 
                    UTI: 'public.item' 
                });
            } else {
                Alert.alert('Bilgi', 'Bu cihazda paylaşım özelliği desteklenmiyor.');
            }
        } catch (error) {
            console.error("Paylaşım hatası:", error);
            Alert.alert('Hata', 'Dosya paylaşılırken bir sorun oluştu.');
        }
    };

    const toggleSelection = (id) => {
        if (selectedIds.includes(id)) {
            setSelectedIds(selectedIds.filter(i => i !== id));
        } else {
            setSelectedIds([...selectedIds, id]);
        }
    };

    const getEmojiForType = (type, filePath) => {
        const isPdf = filePath?.toLowerCase().endsWith('.pdf');
        
        if (type === 'Vergi Levhası') return 'https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Chart%20Increasing%20with%20Yen.png';
        if (type === 'Sicil Gazetesi') return 'https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Rolled-Up%20Newspaper.png';
        if (type === 'İmza Sirküsü') return 'https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Fountain%20Pen.png';
        if (type === 'Faaliyet Belgesi') return 'https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Briefcase.png';
        
        if (isPdf) return 'https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Page%20Facing%20Up.png';
        return 'https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Framed%20Picture.png';
    };

    const renderItem = ({ item }) => {
        const isSelected = selectedIds.includes(item.id);

        return (
            <TouchableOpacity 
                activeOpacity={0.8} 
                onPress={() => toggleSelection(item.id)}
                style={[st.card, isSelected && st.cardSelected]}
            >
                <View style={st.cardTop}>
                    <View style={[st.checkbox, isSelected && st.checkboxSelected]}>
                        {isSelected && <Icon name="check" size={16} color="#fff" />}
                    </View>
                    <View style={st.iconBox}>
                        <Image source={{ uri: getEmojiForType(item.document_type, item.file_path) }} style={st.emojiIcon} />
                    </View>
                    <View style={st.cardInfo}>
                        <Text style={st.docType}>{item.document_type || 'Diğer'}</Text>
                        <Text style={st.docName}>{item.document_name}</Text>
                        <Text style={st.dateText}>{item.created_at ? new Date(item.created_at).toLocaleDateString('tr-TR') : ''}</Text>
                    </View>
                </View>

                <View style={st.actionRow}>
                    <TouchableOpacity style={[st.actionBtn, { backgroundColor: '#EFF6FF' }]} onPress={() => Linking.openURL(item.file_url)}>
                        <Icon name="eye-outline" size={18} color="#3B82F6" />
                        <Text style={[st.actionText, { color: '#3B82F6' }]}>İncele</Text>
                    </TouchableOpacity>
                    <TouchableOpacity style={[st.actionBtn, { backgroundColor: '#FFFBEB' }]} onPress={() => handleShare(item)}>
                        <Icon name="share-variant-outline" size={18} color="#F59E0B" />
                        <Text style={[st.actionText, { color: '#F59E0B' }]}>Paylaş</Text>
                    </TouchableOpacity>
                    <TouchableOpacity style={[st.actionBtn, { backgroundColor: '#FEF2F2' }]} onPress={() => confirmDelete(item.id)}>
                        <Icon name="trash-can-outline" size={18} color="#EF4444" />
                    </TouchableOpacity>
                </View>
            </TouchableOpacity>
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
                        <Text style={st.headerTitle}>Şirket Evrakları</Text>
                        <Text style={st.headerSubtitle}>Kurumsal Belgeleriniz</Text>
                    </View>
                    {hasPermission('company_documents.create') ? (
                        <TouchableOpacity style={st.addHeaderBtn} onPress={() => setModalVisible(true)}>
                            <Icon name="plus" size={24} color="#fff" />
                        </TouchableOpacity>
                    ) : <View style={{ width: 44 }} />}
                </View>

                {selectedIds.length > 0 && (
                    <View style={st.bulkActionContainer}>
                        <Text style={st.bulkText}>{selectedIds.length} Belge Seçildi</Text>
                        <TouchableOpacity style={st.bulkDeleteBtn} onPress={handleBulkDelete}>
                            <Icon name="trash-can" size={18} color="#fff" />
                            <Text style={st.bulkDeleteText}>Toplu Sil</Text>
                        </TouchableOpacity>
                    </View>
                )}

                {loading ? (
                    <View style={st.loader}><ActivityIndicator size="large" color="#8B5CF6" /></View>
                ) : (
                    <FlatList
                        data={documents}
                        renderItem={renderItem}
                        keyExtractor={item => item.id.toString()}
                        contentContainerStyle={st.listContent}
                        showsVerticalScrollIndicator={false}
                        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={() => fetchDocuments(true)} tintColor="#8B5CF6" />}
                        ListEmptyComponent={<EmptyState title="Belge Bulunamadı" message="Henüz şirket evrağı yüklenmemiş." icon="folder-open-outline" />}
                    />
                )}

                {/* Upload Modal */}
                <Modal visible={modalVisible} animationType="slide" transparent>
                    <View style={st.modalOverlay}>
                        <View style={st.modalContent}>
                            <View style={st.modalHeader}>
                                <Text style={st.modalTitle}>Yeni Evrak Yükle</Text>
                                <TouchableOpacity onPress={() => setModalVisible(false)} style={st.modalClose}>
                                    <Icon name="close" size={24} color="#64748B" />
                                </TouchableOpacity>
                            </View>
                            <ScrollView style={{ padding: 20 }}>
                                <View style={{ alignItems: 'center', marginBottom: 20 }}>
                                    <Image source={{ uri: 'https://raw.githubusercontent.com/Tarikul-Islam-Anik/Animated-Fluent-Emojis/master/Emojis/Objects/Outbox%20Tray.png' }} style={{ width: 64, height: 64 }} />
                                    <Text style={{ fontSize: 13, color: '#64748B', textAlign: 'center', marginTop: 8 }}>PDF veya resim dosyalarınızı yükleyebilirsiniz.</Text>
                                </View>

                                <Text style={st.inputLabel}>EVRAK ADI</Text>
                                <FormField value={documentName} onChangeText={setDocumentName} placeholder="Örn: 2026 Vergi Levhası" />
                                
                                <Text style={st.inputLabel}>DOSYA SEÇİMİ</Text>
                                <TouchableOpacity style={st.fileBtn} onPress={pickDocument}>
                                    {file ? (
                                        <>
                                            <Icon name="file-check-outline" size={28} color="#10B981" />
                                            <View style={{ flex: 1, marginLeft: 10 }}>
                                                <Text style={[st.fileBtnText, { color: '#0F172A' }]} numberOfLines={1}>{file.name || 'Dosya Seçildi'}</Text>
                                            </View>
                                        </>
                                    ) : (
                                        <>
                                            <Icon name="file-upload-outline" size={28} color="#8B5CF6" />
                                            <Text style={st.fileBtnText}>PDF veya Resim Seç</Text>
                                        </>
                                    )}
                                </TouchableOpacity>

                                <TouchableOpacity style={[st.saveBtn, saving && { opacity: 0.7 }]} onPress={handleSave} disabled={saving}>
                                    {saving ? <ActivityIndicator color="#fff" /> : <Text style={st.saveBtnText}>Evrağı Kaydet</Text>}
                                </TouchableOpacity>
                                <View style={{ height: 40 }} />
                            </ScrollView>
                        </View>
                    </View>
                </Modal>
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
    headerSubtitle: { fontSize: 13, fontWeight: '600', color: '#8B5CF6', marginTop: 2 },
    addHeaderBtn: { width: 44, height: 44, borderRadius: 22, backgroundColor: '#8B5CF6', alignItems: 'center', justifyContent: 'center', shadowColor: '#8B5CF6', shadowOffset: { width: 0, height: 4 }, shadowOpacity: 0.4, shadowRadius: 6, elevation: 4 },
    
    bulkActionContainer: { flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', backgroundColor: '#F1F5F9', marginHorizontal: 16, paddingHorizontal: 16, paddingVertical: 12, borderRadius: 16, marginBottom: 10, borderWidth: 1, borderColor: '#E2E8F0' },
    bulkText: { fontSize: 14, fontWeight: '700', color: '#0F172A' },
    bulkDeleteBtn: { flexDirection: 'row', alignItems: 'center', backgroundColor: '#EF4444', paddingHorizontal: 12, paddingVertical: 8, borderRadius: 10, gap: 6 },
    bulkDeleteText: { color: '#fff', fontSize: 13, fontWeight: '700' },

    listContent: { padding: 16, paddingBottom: 120 },
    card: { backgroundColor: '#fff', borderRadius: 24, padding: 16, marginBottom: 16, shadowColor: '#000', shadowOffset: { width: 0, height: 8 }, shadowOpacity: 0.05, shadowRadius: 15, elevation: 3, borderWidth: 1, borderColor: '#F1F5F9' },
    cardSelected: { borderColor: '#8B5CF6', backgroundColor: '#F5F3FF' },
    cardTop: { flexDirection: 'row', alignItems: 'center', marginBottom: 16 },
    
    checkbox: { width: 24, height: 24, borderRadius: 6, borderWidth: 2, borderColor: '#CBD5E1', alignItems: 'center', justifyContent: 'center', marginRight: 12, backgroundColor: '#fff' },
    checkboxSelected: { backgroundColor: '#8B5CF6', borderColor: '#8B5CF6' },
    
    iconBox: { width: 56, height: 56, borderRadius: 16, backgroundColor: '#F8FAFC', alignItems: 'center', justifyContent: 'center', borderWidth: 1, borderColor: '#F1F5F9' },
    emojiIcon: { width: 36, height: 36 },
    
    cardInfo: { flex: 1, marginLeft: 16 },
    docType: { fontSize: 11, fontWeight: '800', color: '#8B5CF6', textTransform: 'uppercase', letterSpacing: 0.5, marginBottom: 4 },
    docName: { fontSize: 16, fontWeight: '900', color: '#1E293B', letterSpacing: -0.3, marginBottom: 4 },
    dateText: { fontSize: 12, fontWeight: '600', color: '#94A3B8' },

    actionRow: { flexDirection: 'row', alignItems: 'center', gap: 8 },
    actionBtn: { flexDirection: 'row', alignItems: 'center', justifyContent: 'center', flex: 1, gap: 6, paddingVertical: 12, borderRadius: 14 },
    actionText: { fontSize: 13, fontWeight: '800' },

    // Modal
    modalOverlay: { flex: 1, backgroundColor: 'rgba(15,23,42,0.6)', justifyContent: 'flex-end' },
    modalContent: { backgroundColor: '#fff', borderTopLeftRadius: 32, borderTopRightRadius: 32, maxHeight: '90%' },
    modalHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', padding: 24, borderBottomWidth: 1, borderBottomColor: '#F1F5F9' },
    modalTitle: { fontSize: 20, fontWeight: '900', color: '#0F172A' },
    modalClose: { width: 40, height: 40, borderRadius: 20, backgroundColor: '#F1F5F9', alignItems: 'center', justifyContent: 'center' },
    inputLabel: { fontSize: 12, fontWeight: '800', color: '#64748B', marginTop: 16, marginBottom: 8, marginLeft: 4, letterSpacing: 0.5 },
    fileBtn: { flexDirection: 'row', alignItems: 'center', justifyContent: 'center', padding: 20, backgroundColor: '#F5F3FF', borderRadius: 20, borderWidth: 2, borderColor: '#DDD6FE', borderStyle: 'dashed' },
    fileBtnText: { fontSize: 15, fontWeight: '800', color: '#8B5CF6', marginLeft: 10 },
    saveBtn: { backgroundColor: '#8B5CF6', borderRadius: 20, paddingVertical: 18, alignItems: 'center', marginTop: 28, shadowColor: '#8B5CF6', shadowOffset: { width: 0, height: 8 }, shadowOpacity: 0.4, shadowRadius: 12, elevation: 8 },
    saveBtnText: { color: '#fff', fontSize: 16, fontWeight: '900' },
});
