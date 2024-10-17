/**
 * @typedef {{
 *  id: number
 *  live: boolean
 *  summary: string
 *  description: string
 *  start_date_and_time: Date
 *  playlist: string|null
 *  ingest: string|null
 *  viewers: number
 *  can_be_started: boolean
 *  pictures: string[]
 * }} stream
 */

const templates = document.getElementsByTagName('template');

function renderTemplate(id, deep = true) {
    const template = templates.namedItem(id);
    // const node = document.importNode(template, deep);

    if (!template) {
        throw new Error(`Template ${id} not found`);
    }

    return template.content.children[0];
}

const liveStreamsList = document.querySelector('#live_streams');
const liveStreamsMap = new Map();

function renderLiveStreams(streams) {
    let result = '';

    for (const stream of streams) {
        const html = renderLiveStream(stream);
        liveStreamsMap.set(stream.id, html);
        result += html;
    }

    liveStreamsList.innerHTML = result;
}

function renderLiveStream(stream) {
    return `
        <li class="live-stream">
            ${
        stream.live
            ? renderTemplate('live_stream-live-tooltip').outerHTML
            : ''
    }
            <div class="content">
                ${stream.pictures.map((picture) => `<img class="picture" src="${picture}" alt="" />`)}
                <h3 class="text-fancy text-center">
                    ${stream.title}
                </h3>
                ${renderLiveStreamDetails(stream).outerHTML}
                ${renderLiveStreamActions(stream)}
            </div>
        </li>
    `.trim();
}

/**
 * @param {stream} stream
 */
function renderLiveStreamDetails(stream) {
    const content = renderTemplate('live_stream-details');
    content.querySelector('summary.title').innerHTML = stream.summary;
    content.querySelector('p.description').innerHTML = stream.description;

    return content;
}

/**
 * @param {stream} stream
 */
function renderLiveStreamActions(stream) {
    const viewers = renderTemplate('live_stream-action-viewers');
    viewers.querySelector('.count').innerHTML = String(stream.viewers);

    return `<div class="divider">
                ${viewers.outerHTML}
                <div class="action">
                    ${stream.live ? renderJoinAction(stream).outerHTML : renderStartAction(stream).outerHTML}
                </div>
            </div>`;
}

function renderJoinAction(stream) {
    const content = renderTemplate('live_stream-action-watch');
    content.dataset.playlist = stream.playlist;

    return content;
}

function renderStartAction(stream) {
    if (stream.can_be_started) {
        const template = renderTemplate('live_stream-action-start');
        template.href = template.href.replace(':id', stream.id);

        return template;
    }

    const template = renderTemplate('live_stream-starts_at');
    template.datetime = stream.start_date_and_time;
    template.innerHTML = stream.start_date_and_time.toLocaleString();

    return template;
}