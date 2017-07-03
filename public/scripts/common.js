var common = {
    builderPage: false,
    baseUrl: base_url,
    domainName: domain_name,
    
    iFrame: null,
    editedObject: null,
    isUpdating: false,
    updatingTimer: null,
    firstLoading: false,
    
    hasIFRAME: function() {
        return true;
    },
    getIFRAME: function() {
        return $($('#content-iframe'));
    },
    getIFRAMEObject: function() {
        return $('#content-iframe');
    },
    getIFRAMEDocument: function() {
        return document;
    },
    
    hasEditedObject: function() {
        if(editedObject == null) return false;
        
        return true;
    }
}