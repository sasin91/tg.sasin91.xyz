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

function renderTemplate(id) {
    const template = templates.namedItem(id);

    if (!template) {
        throw new Error(`Template ${id} not found`);
    }

    return template.content.children[0];
}

const liveStreamsList = document.querySelector('#live_streams');
let streams = [];

function renderLiveStreams(data) {
    let result = '';

    for (const stream of data) {
        const html = renderLiveStream(stream);
        result += html;
    }

    liveStreamsList.innerHTML = result;
    streams = data;
}

function renderLiveStream(stream) {
    return `
        <li data-id="${stream.id}" class="live-stream">
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
    content.addEventListener('click', async () => {
      
    });
    
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

async function fetchLiveStream(id) {
    const response = await fetch(`/api/get/live_streams/${id}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    });

    const stream = await response.json();

    if (response.status !== 200) {
        throw new Error(stream.message);
    }

    return stream;
}

class LiveStreamEventHandler {
  /**
   * @param {HTMLLIElement} element
   */
  element;
  
  /**
   * @param {stream} stream
   */
  stream;

  handle({ status, id }) {
    this.element = document.querySelector(`.live-stream[data-id="${id}"]`);
    this.stream = streams.find((stream) => stream.id === id);

    switch (status) {
      case 'live':
        if (this.stream) {
          this.stream.live = true;
          this.render();
        }
        break;
      case 'offline':
        if (this.stream) {
          this.stream.live = false;
          this.render();
        }
        break;
      case 'deleted':
        streams = streams.filter((stream) => stream.id !== id);
        this.element?.remove();
        break;
      case 'new':
        fetchLiveStream(id).then((stream) => {
            streams.push(stream);

            const html = renderLiveStream(stream);
            liveStreamsList.insertAdjacentHTML('beforeend', html);
        });
        break;
      case 'updated':
        fetchLiveStream(id).then((stream) => {
          this.stream = stream;
          this.render();
        });
        break;
      default:
        throw new Error(`Invalid live stream status: ${status}`);
    }
  }

  render() {
    const html = renderLiveStream(this.stream);
    this.element.outerHTML = html;
  }
}

const liveStreamEventHandler = new LiveStreamEventHandler();
// Dynamically load live streams and keep them updated
socket.onMessage('live_streams', (event) => {
  liveStreamEventHandler.handle(event);
});
