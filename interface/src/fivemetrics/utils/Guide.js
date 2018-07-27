import { LocalStorage } from 'fivemetrics/utils'

const tourTest = {
  id: 'overview',
  showPrevButton: true,
  steps: [
    {
      target: "dashboard-header",
      title: "Welcome to FiveMetrics!",
      content: "This is a main screen of one of the pre-configured dashboards. Here you can see important metrics about your services.",
      placement: "bottom",
      xOffset: 'center',
      arrowOffset: 'center'
    },
    {
      title: 'More services',
      content: 'In the left menu you can find Dashboards for other services you have.',
      target: 'a[data-id="sidebar-btn"]',
      placement: 'right'
    },
    {
      title: 'Dashlets',
      content: 'All dashlets have indicators that inform the period of data presented.',
      target: 'react-grid-item',
      placement: 'right'
    },
    {
      title: 'Filter by tags',
      content: 'Here you can filter your data using tags you have created and by tags available in the system.',
      target: '#btnFilterHome',
      placement: 'left'
    },
     {
      title: 'Layout customization',
      content: 'You can change the layout and size of the dashlets of this dashboard by clicking here.',
      target: 'dropdown-settings',
      placement: 'left'
    },
     {
      title: 'Your feedback is very important!',
      content: 'Found a problem or would like to see other metrics, click here to send us feedback ...',
      target: '_hj_feedback_container',
      placement: 'left'
    }
  ],
  onEnd: () => { document.body.removeChild(document.querySelector('#guide-modal')); LocalStorage.write('overview-guide', false) },
  onClose: () => { document.body.removeChild(document.querySelector('#guide-modal')); LocalStorage.write('overview-guide', false) },
  onStart: () => {
    var p = document.createElement('div');
    p.id = 'guide-modal';
    p.setAttribute('class', 'ant-modal-mask');
    document.body.appendChild(p);
  }
}

export const main = () => {
  const flag = LocalStorage.read('overview-guide')
  if (flag !== false) {
    hopscotch.startTour(tourTest)
  }
}

export default main
