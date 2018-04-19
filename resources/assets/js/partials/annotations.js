/**
 * Created by imad on 23/11/17.
 */

var annotation = $('.annotation').annotator();
annotation.annotator('addPlugin', 'Store', {
    prefix: '/annotations',
    loadFromSearch: {},
    urls: {
        create: '?article_id=1',
        update: '/:id/?article_id=1',
        destroy: '/:id/?article_id=1',
        search: '?article_id=1'
    }
});
annotation.annotator('addPlugin', 'Permissions', {
    user: {id: user.id,name: user.name},
    userString: function (user) {
        if (user && user.name) {
            return user.name;
        }
    },
    userId: function (user) {
        if (user && user.id) {
            return user.id;
        }
    },
    permissions: {
        'read':   [user.id],
        'update': [user.id],
        'delete': [user.id],
        'admin':  [user.id]
    },
    userAuthorize: function (action, annotation, user) {
        return ( user !== null ) ? annotation.user.id == user.id : false;
    }
});
annotation.annotator('addPlugin','Tags');