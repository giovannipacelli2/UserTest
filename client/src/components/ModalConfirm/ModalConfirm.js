import React from 'react';
import './ModalConfirm.scss';

const ModalConfirm = ({title, confirm, refuse}) => {
  return (
    <aside className='modal'>
        <div className="container">
            <h2>{title}</h2>
            <div className="btn-container">
              <button className='btn cl-danger' onClick={()=>{ confirm() }}>Elimina</button>
              <button className='btn cl-success' onClick={()=>{ refuse() }}>Annulla</button>
            </div>
        </div>
    </aside>
  )
}

ModalConfirm.defaultProps = {
    title: 'Example title',
    action : ()=>{}
}

export default ModalConfirm