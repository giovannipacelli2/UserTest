import React from 'react';
import './ModalMessage.scss';

const ModalMessage = ({title, action}) => {
  return (
    <aside className='modal'>
        <div className="container">
            <h2>{title}</h2>
            <button className='btn' onClick={()=>{ action() }}>Ok</button>
        </div>
    </aside>
  )
}

ModalMessage.defaultProps = {
    title: 'Example title',
    action : ()=>{}
}

export default ModalMessage