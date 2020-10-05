import '../css/styles.scss';

let files = require.context('../css/components/', false, /\.scss$/);
files.keys().forEach(files);
